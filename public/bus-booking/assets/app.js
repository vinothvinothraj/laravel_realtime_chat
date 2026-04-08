const apiBase = '/api/bus-booking';

const state = {
    filters: JSON.parse(localStorage.getItem('bb_filters') || '{}'),
    trip: JSON.parse(localStorage.getItem('bb_trip') || 'null'),
    seats: JSON.parse(localStorage.getItem('bb_seats') || '[]'),
    passengers: JSON.parse(localStorage.getItem('bb_passengers') || '[]'),
    contact: JSON.parse(localStorage.getItem('bb_contact') || 'null'),
    booking: JSON.parse(localStorage.getItem('bb_booking') || 'null'),
};

const persist = (key, value) => localStorage.setItem(key, JSON.stringify(value));

const money = (value) => new Intl.NumberFormat(undefined, { style: 'currency', currency: 'USD' }).format(Number(value || 0));

async function request(path, options = {}) {
    const response = await fetch(`${apiBase}${path}`, {
        headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
        ...options,
    });

    const payload = await response.json().catch(() => ({}));
    if (!response.ok) {
        throw new Error(payload.message || 'Request failed.');
    }

    return payload;
}

function renderTrips(trips) {
    const root = document.querySelector('[data-trips]');
    if (!root) return;

    root.innerHTML = trips.map((trip) => `
        <article class="trip">
            <div class="row between center">
                <strong>${trip.route?.origin || ''} → ${trip.route?.destination || ''}</strong>
                <span class="pill">${money(trip.base_fare)}</span>
            </div>
            <div class="small muted" style="margin-top:8px;">${trip.departure_at || ''}</div>
            <div class="small muted">Operator: ${trip.route?.operator?.name || ''}</div>
            <div style="margin-top:12px;">
                <a class="button" href="seat-selection.html?trip_id=${trip.id}">Select seats</a>
            </div>
        </article>
    `).join('');
}

async function initSearchPage() {
    const form = document.querySelector('[data-search-form]');
    if (!form) return;

    form.origin.value = state.filters.origin || '';
    form.destination.value = state.filters.destination || '';
    form.travel_date.value = state.filters.travel_date || '';

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        const filters = {
            origin: form.origin.value.trim(),
            destination: form.destination.value.trim(),
            travel_date: form.travel_date.value,
        };
        persist('bb_filters', filters);
        window.location.href = 'trips.html';
    });
}

async function initTripsPage() {
    const root = document.querySelector('[data-trips]');
    if (!root) return;

    const params = new URLSearchParams({
        ...(state.filters.origin ? { origin: state.filters.origin } : {}),
        ...(state.filters.destination ? { destination: state.filters.destination } : {}),
        ...(state.filters.travel_date ? { travel_date: state.filters.travel_date } : {}),
    });

    const response = await request(`/search?${params.toString()}`);
    renderTrips(response.data || []);
}

async function initSeatPage() {
    const root = document.querySelector('[data-seat-map]');
    if (!root) return;

    const tripId = new URLSearchParams(location.search).get('trip_id');
    if (!tripId) return;

    const response = await request(`/trips/${tripId}`);
    const seats = response.seats || [];
    state.trip = response.trip || state.trip;
    persist('bb_trip', state.trip);

    root.innerHTML = seats.map((seat) => `
        <button class="seat ${seat.status?.value === 'available' || seat.status === 'available' ? '' : 'selected'}" data-seat-id="${seat.seat?.id}" ${seat.status?.value === 'available' || seat.status === 'available' ? '' : 'disabled'}>
            ${seat.seat?.seat_number || 'Seat'}
            <div class="small muted">${seat.status?.value || seat.status}</div>
        </button>
    `).join('');

    const selected = new Set(state.seats);
    root.querySelectorAll('[data-seat-id]').forEach((button) => {
        if (button.disabled) return;
        button.addEventListener('click', () => {
            const seatId = Number(button.dataset.seatId);
            if (selected.has(seatId)) {
                selected.delete(seatId);
                button.classList.remove('selected');
            } else {
                selected.add(seatId);
                button.classList.add('selected');
            }
            persist('bb_seats', Array.from(selected));
        });
    });

    document.querySelector('[data-seat-next]')?.addEventListener('click', () => {
        window.location.href = 'passenger-details.html';
    });
}

function buildPassengerRows() {
    const seats = state.seats.length ? state.seats : [null];
    return seats.map((seatId, index) => `
        <div class="card" style="padding:12px;">
            <div class="small muted">Passenger ${index + 1}${seatId ? ` - Seat ${seatId}` : ''}</div>
            <input class="input" name="full_name" placeholder="Full name" value="${state.passengers[index]?.full_name || ''}" style="margin-top:8px;">
            <div class="row" style="margin-top:8px;">
                <input class="input" name="gender" placeholder="Gender" value="${state.passengers[index]?.gender || ''}">
                <input class="input" name="age" type="number" placeholder="Age" value="${state.passengers[index]?.age || ''}">
            </div>
        </div>
    `).join('');
}

async function initPassengerPage() {
    const root = document.querySelector('[data-passengers]');
    if (!root) return;

    root.innerHTML = buildPassengerRows();
    const form = document.querySelector('[data-passenger-form]');
    if (form && state.contact) {
        form.contact_name.value = state.contact.name || '';
        form.contact_phone.value = state.contact.phone || '';
        form.contact_email.value = state.contact.email || '';
    }

    form?.addEventListener('submit', (event) => {
        event.preventDefault();
        const form = event.currentTarget;
        const passengers = Array.from(root.querySelectorAll('.card')).map((card) => ({
            full_name: card.querySelector('input[name="full_name"]').value.trim(),
            gender: card.querySelector('input[name="gender"]').value.trim(),
            age: card.querySelector('input[name="age"]').value,
        })).filter((passenger) => passenger.full_name);

        state.contact = {
            name: form.contact_name.value.trim(),
            phone: form.contact_phone.value.trim(),
            email: form.contact_email.value.trim(),
        };
        state.passengers = passengers;

        persist('bb_contact', state.contact);
        persist('bb_passengers', passengers);
        window.location.href = 'payment.html';
    });
}

async function initPaymentPage() {
    const form = document.querySelector('[data-payment-form]');
    if (!form) return;

    if (state.contact) {
        form.contact_name.value = state.contact.name || '';
        form.contact_phone.value = state.contact.phone || '';
    }

    const tripInfo = document.querySelector('[data-payment-summary]');
    if (tripInfo && state.trip) {
        tripInfo.innerHTML = `
            <div class="small muted">${state.trip.route?.origin || ''} → ${state.trip.route?.destination || ''}</div>
            <div class="small muted">${state.seats.length} seat(s) selected</div>
        `;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const payload = {
            bus_trip_id: Number(state.trip?.id || new URLSearchParams(location.search).get('trip_id')),
            seat_ids: state.seats,
            contact_name: state.contact?.name || form.contact_name.value.trim(),
            contact_phone: state.contact?.phone || form.contact_phone.value.trim(),
            contact_email: state.contact?.email || form.contact_email.value.trim(),
            passengers: state.passengers,
            method: form.method.value,
            provider: form.provider.value,
            total_amount: state.trip?.base_fare || 0,
        };

        const response = await request('/bookings', {
            method: 'POST',
            body: JSON.stringify(payload),
        });

        state.booking = response.data;
        persist('bb_booking', state.booking);
        window.location.href = `success.html?reference=${encodeURIComponent(state.booking.booking_reference)}`;
    });
}

async function initSuccessPage() {
    const root = document.querySelector('[data-success]');
    if (!root) return;

    const reference = new URLSearchParams(location.search).get('reference') || state.booking?.booking_reference;
    if (!reference) return;

    const response = await request(`/bookings/${reference}`);
    root.innerHTML = `
        <div class="card">
            <div class="title">Booking confirmed</div>
            <div class="muted">Reference</div>
            <div style="font-size:28px;font-weight:700;margin:6px 0 12px;">${response.data.booking_reference}</div>
            <div class="muted">Trip</div>
            <div>${response.data.trip?.route?.origin || ''} → ${response.data.trip?.route?.destination || ''}</div>
            <div style="margin-top:16px;">
                <a class="button" href="ticket.html?reference=${encodeURIComponent(reference)}">View ticket</a>
            </div>
        </div>
    `;
}

async function initTicketPage() {
    const root = document.querySelector('[data-ticket]');
    if (!root) return;

    const reference = new URLSearchParams(location.search).get('reference');
    if (!reference) return;

    const response = await request(`/bookings/${reference}`);
    root.innerHTML = `
        <div class="card">
            <div class="title">Digital ticket</div>
            <div class="muted">Reference</div>
            <div style="font-size:28px;font-weight:700;margin:6px 0 12px;">${response.data.booking_reference}</div>
            <div class="divider"></div>
            <div><strong>${response.data.contact_name}</strong></div>
            <div class="muted">${response.data.trip?.route?.origin || ''} → ${response.data.trip?.route?.destination || ''}</div>
            <div class="muted" style="margin-top:8px;">${response.data.trip?.departure_at || ''}</div>
        </div>
    `;
}

document.addEventListener('DOMContentLoaded', async () => {
    try {
        await initSearchPage();
        await initTripsPage();
        await initSeatPage();
        await initPassengerPage();
        await initPaymentPage();
        await initSuccessPage();
        await initTicketPage();
    } catch (error) {
        const root = document.querySelector('[data-error]');
        if (root) root.textContent = error.message;
        console.error(error);
    }
});
