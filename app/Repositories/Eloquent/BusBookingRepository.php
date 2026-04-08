<?php

namespace App\Repositories\Eloquent;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\SeatStatus;
use App\Models\Booking;
use App\Models\BookingPassenger;
use App\Models\BusTrip;
use App\Models\BusTripSeat;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Ticket;
use App\Repositories\Contracts\BusTripRepositoryInterface;
use App\Repositories\Contracts\BusBookingRepositoryInterface;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BusBookingRepository implements BusBookingRepositoryInterface
{
    public function __construct(
        protected BusTripRepositoryInterface $trips
    ) {
    }

    public function searchTrips(array $filters): Collection
    {
        return $this->trips->searchTrips($filters);
    }

    public function tripSeatMap(BusTrip $trip): Collection
    {
        return $this->trips->seatMap($trip);
    }

    public function syncTripSeatInventory(BusTrip $trip): void
    {
        $this->trips->syncSeatInventory($trip);
    }

    public function findBookingByReference(string $reference): ?Booking
    {
        return Booking::query()
            ->with(['trip.route.operator', 'trip.bus.operator', 'passengers.seat', 'payments', 'ticket', 'refund'])
            ->where('booking_reference', $reference)
            ->first();
    }

    public function findBookingOrFail(int $bookingId): Booking
    {
        return Booking::query()
            ->with(['trip.route.operator', 'trip.bus.operator', 'passengers.seat', 'payments', 'ticket', 'refund'])
            ->findOrFail($bookingId);
    }

    public function createBooking(array $data): Booking
    {
        return Booking::create($data);
    }

    public function createPayment(Booking $booking, array $data): Payment
    {
        return $booking->payments()->create($data);
    }

    public function issueTicket(Booking $booking): Ticket
    {
        $ticketNumber = 'TKT-' . strtoupper(Str::random(10));

        return $booking->ticket()->updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'ticket_number' => $ticketNumber,
                'qr_payload' => json_encode([
                    'booking_reference' => $booking->booking_reference,
                    'ticket_number' => $ticketNumber,
                ]),
                'status' => 'issued',
                'issued_at' => now(),
            ]
        );
    }

    public function createRefund(Booking $booking, ?Payment $payment, array $data): Refund
    {
        return Refund::create([
            'booking_id' => $booking->id,
            'payment_id' => $payment?->id,
            'amount' => $data['amount'] ?? 0,
            'status' => $data['status'] ?? 'pending',
            'reason' => $data['reason'] ?? null,
            'processed_at' => $data['processed_at'] ?? null,
            'reference' => $data['reference'] ?? ('RF-' . strtoupper(Str::random(10))),
        ]);
    }

    public function lockSeats(BusTrip $trip, array $seatIds, ?int $bookingId = null, ?CarbonInterface $heldUntil = null): void
    {
        $heldUntil ??= $bookingId ? null : now()->addMinutes(15);

        $seats = BusTripSeat::query()
            ->where('bus_trip_id', $trip->id)
            ->whereIn('bus_seat_id', $seatIds)
            ->lockForUpdate()
            ->get();

        if ($seats->count() !== count($seatIds)) {
            throw new \RuntimeException('One or more selected seats are unavailable.');
        }

        $unavailable = $seats->contains(function (BusTripSeat $seat) use ($bookingId): bool {
            if ($seat->status === SeatStatus::Booked) {
                return true;
            }

            if ($seat->status === SeatStatus::Held && $seat->held_until && $seat->held_until->isFuture() && ! $bookingId) {
                return true;
            }

            return false;
        });

        if ($unavailable) {
            throw new \RuntimeException('One or more selected seats are already locked.');
        }

        BusTripSeat::query()
            ->where('bus_trip_id', $trip->id)
            ->whereIn('bus_seat_id', $seatIds)
            ->update([
                'status' => $bookingId ? SeatStatus::Booked->value : SeatStatus::Held->value,
                'booking_id' => $bookingId,
                'held_until' => $heldUntil,
            ]);
    }

    public function releaseSeats(BusTrip $trip, array $seatIds): void
    {
        BusTripSeat::query()
            ->where('bus_trip_id', $trip->id)
            ->whereIn('bus_seat_id', $seatIds)
            ->update([
                'status' => SeatStatus::Available->value,
                'booking_id' => null,
                'held_until' => null,
            ]);
    }

    public function bookingForAdmin(int $bookingId): Booking
    {
        return $this->findBookingOrFail($bookingId);
    }
}
