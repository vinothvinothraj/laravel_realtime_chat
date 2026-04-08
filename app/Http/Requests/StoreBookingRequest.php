<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bus_trip_id' => ['required', 'exists:bus_trips,id'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'seat_ids' => ['required', 'array', 'min:1'],
            'seat_ids.*' => ['integer', 'exists:bus_seats,id'],
            'passengers' => ['required', 'array', 'min:1'],
            'passengers.*.full_name' => ['required', 'string', 'max:255'],
            'passengers.*.gender' => ['nullable', 'string', 'max:20'],
            'passengers.*.age' => ['nullable', 'integer', 'min:0'],
            'method' => ['nullable', 'string', 'max:50'],
            'provider' => ['nullable', 'string', 'max:50'],
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'total_amount' => ['nullable', 'numeric', 'min:0'],
            'promo_code_id' => ['nullable', 'exists:promo_codes,id'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
