<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'box_id' => 'required|integer|exists:boxes,id',
        ];
    }

    public function messages(): array
    {
        return [
            'box_id.required' => 'La box est requise.',
            'box_id.exists' => 'La box sélectionnée n\'existe pas.',
        ];
    }
}
