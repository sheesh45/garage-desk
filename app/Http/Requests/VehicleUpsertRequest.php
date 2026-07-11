<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleUpsertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $vehicleId = $this->route('vehicle')?->id;

        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'registration_number' => ['required', 'string', 'max:50', Rule::unique('vehicles', 'registration_number')->ignore($vehicleId)],
            'brand' => ['required', 'string', 'max:120'],
            'model' => ['required', 'string', 'max:120'],
            'year' => ['nullable', 'integer', 'min:1980', 'max:2100'],
            'vehicle_type' => ['required', 'string', 'max:50'],
            'fuel_type' => ['nullable', 'string', 'max:50'],
            'engine_number' => ['nullable', 'string', 'max:120'],
            'chassis_number' => ['nullable', 'string', 'max:120'],
            'odometer' => ['required', 'integer', 'min:0'],
            'insurance_expiry' => ['nullable', 'date'],
            'puc_expiry' => ['nullable', 'date'],
            'last_service_at' => ['nullable', 'date'],
            'next_service_due_at' => ['nullable', 'date'],
        ];
    }
}
