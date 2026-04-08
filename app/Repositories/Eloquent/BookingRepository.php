<?php

namespace App\Repositories\Eloquent;

use App\Models\Booking;
use App\Models\BusTrip;
use App\Models\User;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Collection;

class BookingRepository implements BookingRepositoryInterface
{
    public function findByReference(string $reference): ?Booking
    {
        return Booking::with(['trip.route.operator', 'passengers.seat', 'payments', 'ticket', 'refunds'])->where('booking_reference', $reference)->first();
    }

    public function latestForUser(User $user, int $limit = 20): Collection
    {
        return Booking::with(['trip.route.operator', 'ticket'])
            ->where('user_id', $user->id)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    public function update(Booking $booking, array $data): Booking
    {
        $booking->update($data);

        return $booking->refresh();
    }

    public function bookingsForTrip(BusTrip $trip): Collection
    {
        return Booking::with(['passengers', 'payments', 'ticket'])
            ->where('bus_trip_id', $trip->id)
            ->latest()
            ->get();
    }
}
