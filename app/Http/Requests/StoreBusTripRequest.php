<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusTripRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bus_route_id' => ['required', 'exists:bus_routes,id'],
            'bus_id' => ['required', 'exists:buses,id'],
            'departure_at' => ['required', 'date'],
            'arrival_at' => ['nullable', 'date', 'after_or_equal:departure_at'],
            'base_fare' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:scheduled,boarding,departed,completed,cancelled'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
