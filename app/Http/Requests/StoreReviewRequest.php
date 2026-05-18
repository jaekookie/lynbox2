<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'box_id' => 'required|integer|exists:boxes,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'box_id.required' => 'La box est requise.',
            'box_id.exists' => 'La box sélectionnée n\'existe pas.',
            'rating.required' => 'La note est requise.',
            'rating.min' => 'La note doit être entre 1 et 5.',
            'rating.max' => 'La note doit être entre 1 et 5.',
            'comment.required' => 'Le commentaire est requis.',
            'comment.min' => 'Le commentaire doit contenir au moins 10 caractères.',
            'comment.max' => 'Le commentaire ne peut pas dépasser 1000 caractères.',
        ];
    }
}
