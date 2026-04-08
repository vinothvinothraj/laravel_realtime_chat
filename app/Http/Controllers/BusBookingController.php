<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\BusBookingService;
use Illuminate\View\View;

class BusBookingController extends Controller
{
    public function __construct(
        protected BusBookingService $bookings
    ) {
    }

    public function index(): View
    {
        return view('bus-booking.bookings.index', [
            'bookings' => Booking::query()
                ->with(['trip.route.operator', 'trip.bus.operator'])
                ->latest()
                ->paginate(15),
        ]);
    }

    public function show(Booking $booking): View
    {
        return view('bus-booking.bookings.show', [
            'booking' => $booking->load(['trip.route.operator', 'trip.bus.operator', 'passengers.seat', 'payments', 'ticket', 'refund']),
        ]);
    }
}
