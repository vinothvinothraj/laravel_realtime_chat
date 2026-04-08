<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $busId = $this->route('bus')?->id ?? $this->route('busModel')?->id;

        return [
            'bus_operator_id' => ['required', 'exists:bus_operators,id'],
            'name' => ['required', 'string', 'max:255'],
            'registration_number' => ['required', 'string', 'max:255', Rule::unique('buses', 'registration_number')->ignore($busId)],
            'bus_type' => ['required', 'string', 'max:255'],
            'seat_capacity' => ['required', 'integer', 'min:1'],
            'seat_layout' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
