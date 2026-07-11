<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MechanicUpsertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['string', 'max:100'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:60'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
