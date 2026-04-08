<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;
use App\Models\BusTrip;
use App\Models\Payment;
use App\Models\Refund;
use App\Models\Ticket;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

interface BusBookingRepositoryInterface
{
    public function searchTrips(array $filters): Collection;

    public function tripSeatMap(BusTrip $trip): Collection;

    public function syncTripSeatInventory(BusTrip $trip): void;

    public function findBookingByReference(string $reference): ?Booking;

    public function findBookingOrFail(int $bookingId): Booking;

    public function createBooking(array $data): Booking;

    public function createPayment(Booking $booking, array $data): Payment;

    public function issueTicket(Booking $booking): Ticket;

    public function createRefund(Booking $booking, ?Payment $payment, array $data): Refund;

    public function lockSeats(BusTrip $trip, array $seatIds, ?int $bookingId = null, ?CarbonInterface $heldUntil = null): void;

    public function releaseSeats(BusTrip $trip, array $seatIds): void;

    public function bookingForAdmin(int $bookingId): Booking;
}
