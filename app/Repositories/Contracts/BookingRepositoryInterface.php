<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;
use App\Models\BusTrip;
use App\Models\User;
use Illuminate\Support\Collection;

interface BookingRepositoryInterface
{
    public function findByReference(string $reference): ?Booking;

    public function latestForUser(User $user, int $limit = 20): Collection;

    public function create(array $data): Booking;

    public function update(Booking $booking, array $data): Booking;

    public function bookingsForTrip(BusTrip $trip): Collection;
}
