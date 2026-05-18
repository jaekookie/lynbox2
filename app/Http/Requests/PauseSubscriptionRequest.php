<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PauseSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pause_days' => 'nullable|integer|min:1|max:365',
        ];
    }

    public function messages(): array
    {
        return [
            'pause_days.max' => 'La pause ne peut pas dépasser 365 jours.',
        ];
    }
}
