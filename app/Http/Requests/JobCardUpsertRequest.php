<?php

namespace App\Http\Requests;

use App\Enums\JobCardPriority;
use App\Enums\JobCardStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobCardUpsertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'mechanic_id' => ['nullable', 'exists:mechanics,id'],
            'complaint' => ['required', 'string'],
            'estimated_delivery_at' => ['nullable', 'date'],
            'priority' => ['required', Rule::in(JobCardPriority::values())],
            'status' => ['required', Rule::in(JobCardStatus::values())],
            'diagnosis' => ['nullable', 'string'],
            'repair_notes' => ['nullable', 'string'],
            'time_taken_minutes' => ['nullable', 'integer', 'min:0'],
            'inspection_notes' => ['nullable', 'string'],
            'delivery_notes' => ['nullable', 'string'],
            'services' => ['nullable', 'array'],
            'services.*' => ['exists:service_catalog,id'],
            'parts' => ['nullable', 'array'],
            'parts.*.id' => ['required_with:parts', 'exists:inventory_items,id'],
            'parts.*.quantity' => ['required_with:parts', 'integer', 'min:1'],
        ];
    }
}
