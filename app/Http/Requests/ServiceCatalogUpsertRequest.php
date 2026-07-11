<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceCatalogUpsertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'estimated_minutes' => ['required', 'integer', 'min:15'],
            'base_cost' => ['required', 'numeric', 'min:0'],
            'required_parts' => ['nullable', 'array'],
            'required_parts.*' => ['string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
