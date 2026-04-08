<?php

namespace Database\Seeders;

use App\Enums\BookingStatus;
use App\Enums\PaymentStatus;
use App\Enums\SeatStatus;
use App\Enums\TripStatus;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\BusOperator;
use App\Models\BusRoute;
use App\Models\BusSeat;
use App\Models\BusTrip;
use App\Models\BusTripSeat;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BusBookingSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin',
                'password' => bcrypt('password'),
                'is_bus_staff' => true,
            ]
        );

        $colomboOperator = BusOperator::query()->updateOrCreate(
            ['slug' => 'colombo-express'],
            [
                'name' => 'Colombo Express',
                'contact_phone' => '077-100-2000',
                'contact_email' => 'support@colomboexpress.test',
                'is_active' => true,
            ]
        );

        $kandyOperator = BusOperator::query()->updateOrCreate(
            ['slug' => 'kandy-travels'],
            [
                'name' => 'Kandy Travels',
                'contact_phone' => '077-300-4000',
                'contact_email' => 'info@kandytravels.test',
                'is_active' => true,
            ]
        );

        $routeColomboKandy = BusRoute::query()->updateOrCreate(
            ['code' => 'R-CMB-KDY'],
            [
                'bus_operator_id' => $colomboOperator->id,
                'origin' => 'Colombo',
                'destination' => 'Kandy',
                'duration_minutes' => 240,
                'is_active' => true,
            ]
        );

        $routeKandyGalle = BusRoute::query()->updateOrCreate(
            ['code' => 'R-KDY-GL'],
            [
                'bus_operator_id' => $kandyOperator->id,
                'origin' => 'Kandy',
                'destination' => 'Galle',
                'duration_minutes' => 300,
                'is_active' => true,
            ]
        );

        $busA = Bus::query()->updateOrCreate(
            ['registration_number' => 'ABC-1234'],
            [
                'bus_operator_id' => $colomboOperator->id,
                'name' => 'Luxury Coach A',
                'bus_type' => 'Luxury AC',
                'seat_capacity' => 36,
                'seat_layout' => [
                    'rows' => 9,
                    'columns' => 4,
                    'type' => '2x2',
                ],
                'is_active' => true,
            ]
        );

        $busB = Bus::query()->updateOrCreate(
            ['registration_number' => 'XYZ-5678'],
            [
                'bus_operator_id' => $kandyOperator->id,
                'name' => 'Express Coach B',
                'bus_type' => 'Semi Luxury',
                'seat_capacity' => 32,
                'seat_layout' => [
                    'rows' => 8,
                    'columns' => 4,
                    'type' => '2x2',
                ],
                'is_active' => true,
            ]
        );

        $this->seedBusSeats($busA, 36);
        $this->seedBusSeats($busB, 32);

        $tripMorning = BusTrip::query()->updateOrCreate(
            [
                'bus_route_id' => $routeColomboKandy->id,
                'bus_id' => $busA->id,
                'departure_at' => Carbon::now()->addDays(1)->setTime(8, 30),
            ],
            [
                'arrival_at' => Carbon::now()->addDays(1)->setTime(12, 30),
                'base_fare' => 1850.00,
                'status' => TripStatus::Scheduled->value,
                'notes' => 'Morning service with tea break stop.',
            ]
        );

        $tripEvening = BusTrip::query()->updateOrCreate(
            [
                'bus_route_id' => $routeKandyGalle->id,
                'bus_id' => $busB->id,
                'departure_at' => Carbon::now()->addDays(1)->setTime(15, 0),
            ],
            [
                'arrival_at' => Carbon::now()->addDays(1)->setTime(20, 0),
                'base_fare' => 2450.00,
                'status' => TripStatus::Scheduled->value,
                'notes' => 'Evening express with reserved seating.',
            ]
        );

        $this->seedTripInventory($tripMorning);
        $this->seedTripInventory($tripEvening);

        $booking = Booking::query()->updateOrCreate(
            ['booking_reference' => 'BR-DEMO-1001'],
            [
                'user_id' => $admin->id,
                'bus_trip_id' => $tripMorning->id,
                'status' => BookingStatus::Confirmed->value,
                'payment_status' => PaymentStatus::Paid->value,
                'hold_expires_at' => now()->addMinutes(15),
                'contact_name' => 'Admin Demo',
                'contact_phone' => '0777000001',
                'contact_email' => 'admin@example.com',
                'passenger_count' => 2,
                'total_amount' => 3700.00,
                'notes' => 'Seeded confirmed booking.',
            ]
        );

        $bookedSeats = BusTripSeat::query()
            ->where('bus_trip_id', $tripMorning->id)
            ->orderBy('id')
            ->take(2)
            ->get();

        foreach ($bookedSeats as $index => $tripSeat) {
            $tripSeat->forceFill([
                'status' => SeatStatus::Booked->value,
                'booking_id' => $booking->id,
                'held_until' => null,
            ])->save();

            $booking->passengers()->updateOrCreate(
                ['bus_seat_id' => $tripSeat->bus_seat_id],
                [
                    'full_name' => $index === 0 ? 'Nimal Perera' : 'Saman Silva',
                    'gender' => $index === 0 ? 'male' : 'male',
                    'age' => $index === 0 ? 34 : 29,
                ]
            );
        }

        Payment::query()->updateOrCreate(
            ['transaction_reference' => 'TXN-DEMO-1001'],
            [
                'booking_id' => $booking->id,
                'provider' => 'manual',
                'method' => 'cash',
                'amount' => 3700.00,
                'status' => PaymentStatus::Paid->value,
                'paid_at' => now(),
                'payload' => [
                    'seeded' => true,
                ],
            ]
        );

        Ticket::query()->updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'ticket_number' => 'TKT-DEMO-1001',
                'qr_payload' => json_encode([
                    'booking_reference' => $booking->booking_reference,
                    'ticket_number' => 'TKT-DEMO-1001',
                ]),
                'status' => 'issued',
                'issued_at' => now(),
            ]
        );

        Booking::query()->updateOrCreate(
            ['booking_reference' => 'BR-DEMO-1002'],
            [
                'user_id' => $admin->id,
                'bus_trip_id' => $tripEvening->id,
                'status' => BookingStatus::PaymentPending->value,
                'payment_status' => PaymentStatus::Pending->value,
                'hold_expires_at' => now()->addMinutes(10),
                'contact_name' => 'Walk-in Customer',
                'contact_phone' => '0777000002',
                'contact_email' => 'customer@example.com',
                'passenger_count' => 1,
                'total_amount' => 2450.00,
                'notes' => 'Seeded pending booking.',
            ]
        );
    }

    protected function seedBusSeats(Bus $bus, int $seatCapacity): void
    {
        $existingSeats = BusSeat::query()->where('bus_id', $bus->id)->count();

        if ($existingSeats >= $seatCapacity) {
            return;
        }

        $seatNumber = $existingSeats + 1;
        $rows = (int) ceil($seatCapacity / 4);

        for (; $seatNumber <= $seatCapacity; $seatNumber++) {
            $rowNumber = (int) ceil($seatNumber / 4);
            $positionIndex = ($seatNumber - 1) % 4;
            $position = match ($positionIndex) {
                0 => 'A',
                1 => 'B',
                2 => 'C',
                default => 'D',
            };

            BusSeat::query()->firstOrCreate(
                [
                    'bus_id' => $bus->id,
                    'seat_number' => (string) $seatNumber,
                ],
                [
                    'row_number' => $rowNumber,
                    'seat_position' => $position,
                    'status' => SeatStatus::Available->value,
                    'is_window' => in_array($position, ['A', 'D'], true),
                    'is_aisle' => in_array($position, ['B', 'C'], true),
                ]
            );
        }
    }

    protected function seedTripInventory(BusTrip $trip): void
    {
        $trip->loadMissing('bus.seats');

        foreach ($trip->bus->seats as $seat) {
            BusTripSeat::query()->firstOrCreate(
                [
                    'bus_trip_id' => $trip->id,
                    'bus_seat_id' => $seat->id,
                ],
                [
                    'status' => SeatStatus::Available->value,
                ]
            );
        }
    }
}
