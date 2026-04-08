<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBusRouteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $routeId = $this->route('route')?->id ?? $this->route('bus_route')?->id ?? $this->route('busRoute')?->id;

        return [
            'bus_operator_id' => ['required', 'exists:bus_operators,id'],
            'code' => ['required', 'string', 'max:255', Rule::unique('bus_routes', 'code')->ignore($routeId)],
            'origin' => ['required', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'duration_minutes' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
