<?php

namespace App\Services;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Models\Booking;
use App\Models\BusTrip;
use App\Repositories\Contracts\BusBookingRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BusBookingService
{
    public function __construct(
        protected BusBookingRepositoryInterface $bookings
    ) {
    }

    public function search(array $filters)
    {
        return $this->bookings->searchTrips($filters);
    }

    public function tripDetails(BusTrip $trip): array
    {
        $this->bookings->syncTripSeatInventory($trip);

        return [
            'trip' => $trip->load(['route.operator', 'bus.operator']),
            'seats' => $this->bookings->tripSeatMap($trip),
        ];
    }

    public function createBooking(array $data): Booking
    {
        return DB::transaction(function () use ($data): Booking {
            $trip = BusTrip::query()->with(['route.operator', 'bus.seats'])->find($data['bus_trip_id']);

            if (! $trip) {
                throw new ModelNotFoundException();
            }

            $seatIds = collect($data['seat_ids'] ?? [])->filter()->values()->all();
            $this->bookings->syncTripSeatInventory($trip);
            $this->bookings->lockSeats($trip, $seatIds, null, Carbon::now()->addMinutes(15));

            $booking = $this->bookings->createBooking([
                'user_id' => $data['user_id'] ?? null,
                'bus_trip_id' => $trip->id,
                'promo_code_id' => $data['promo_code_id'] ?? null,
                'booking_reference' => 'BR-' . strtoupper(Str::random(10)),
                'status' => BookingStatus::PaymentPending->value,
                'payment_status' => PaymentStatus::Pending->value,
                'hold_expires_at' => now()->addMinutes(15),
                'contact_name' => $data['contact_name'],
                'contact_phone' => $data['contact_phone'],
                'contact_email' => $data['contact_email'] ?? null,
                'passenger_count' => count($data['passengers'] ?? []) ?: 1,
                'total_amount' => $data['total_amount'] ?? $trip->base_fare,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($data['passengers'] ?? [] as $index => $passenger) {
                $booking->passengers()->create([
                    'bus_seat_id' => $seatIds[$index] ?? null,
                    'full_name' => $passenger['full_name'],
                    'gender' => $passenger['gender'] ?? null,
                    'age' => $passenger['age'] ?? null,
                ]);
            }

            $booking->payments()->create([
                'provider' => $data['provider'] ?? 'manual',
                'method' => $data['method'] ?? 'cash',
                'amount' => $booking->total_amount,
                'status' => $data['method'] === 'cash' ? PaymentStatus::Paid->value : PaymentStatus::Pending->value,
                'transaction_reference' => $data['transaction_reference'] ?? null,
                'paid_at' => $data['method'] === 'cash' ? now() : null,
                'payload' => $data['payment_payload'] ?? null,
            ]);

            if (($data['method'] ?? 'cash') === 'cash') {
                $booking->forceFill([
                    'status' => BookingStatus::Confirmed->value,
                    'payment_status' => PaymentStatus::Paid->value,
                ])->save();

                $this->bookings->lockSeats($trip, $seatIds, $booking->id, null);
                $this->bookings->issueTicket($booking);
            }

            return $booking->refresh()->load(['trip.route.operator', 'trip.bus.operator', 'passengers.seat', 'payments', 'ticket']);
        });
    }

    public function findByReference(string $reference): ?Booking
    {
        return $this->bookings->findBookingByReference($reference);
    }

    public function cancelBooking(Booking $booking, ?string $reason = null): Booking
    {
        return DB::transaction(function () use ($booking, $reason): Booking {
            $booking->loadMissing('trip.seats');

            $seatIds = $booking->passengers()->pluck('bus_seat_id')->filter()->values()->all();
            $this->bookings->releaseSeats($booking->trip, $seatIds);

            $booking->forceFill([
                'status' => BookingStatus::Cancelled->value,
                'payment_status' => $booking->payment_status instanceof PaymentStatus
                    ? $booking->payment_status->value
                    : (string) $booking->payment_status,
                'notes' => trim(($booking->notes ? $booking->notes . PHP_EOL : '') . ($reason ? 'Cancel reason: ' . $reason : '')),
            ])->save();

            return $booking->refresh();
        });
    }
}
