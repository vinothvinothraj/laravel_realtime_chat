<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelBookingRequest;
use App\Http\Requests\SearchBusTripsRequest;
use App\Http\Requests\StoreBookingRequest;
use App\Models\BusTrip;
use App\Services\BusBookingService;
use Illuminate\Http\JsonResponse;

class BusBookingApiController extends Controller
{
    public function __construct(
        protected BusBookingService $bookings
    ) {
    }

    public function search(SearchBusTripsRequest $request): JsonResponse
    {
        return response()->json([
            'data' => $this->bookings->search($request->validated())->values(),
        ]);
    }

    public function trip(BusTrip $trip): JsonResponse
    {
        return response()->json($this->bookings->tripDetails($trip));
    }

    public function seatMap(BusTrip $trip): JsonResponse
    {
        return response()->json([
            'data' => $this->bookings->tripDetails($trip)['seats'],
        ]);
    }

    public function store(StoreBookingRequest $request): JsonResponse
    {
        $booking = $this->bookings->createBooking($request->validated());

        return response()->json([
            'message' => 'Booking created successfully.',
            'data' => $booking,
        ], 201);
    }

    public function show(string $reference): JsonResponse
    {
        $booking = $this->bookings->findByReference($reference);

        if (! $booking) {
            return response()->json(['message' => 'Booking not found.'], 404);
        }

        return response()->json(['data' => $booking]);
    }

    public function cancel(CancelBookingRequest $request, string $reference): JsonResponse
    {
        $booking = $this->bookings->findByReference($reference);

        if (! $booking) {
            return response()->json(['message' => 'Booking not found.'], 404);
        }

        $cancelled = $this->bookings->cancelBooking($booking, $request->validated('reason'));

        return response()->json([
            'message' => 'Booking cancelled successfully.',
            'data' => $cancelled,
        ]);
    }
}
