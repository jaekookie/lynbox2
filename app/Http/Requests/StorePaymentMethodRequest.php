<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method_id' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method_id.required' => 'Le moyen de paiement est requis.',
        ];
    }
}
