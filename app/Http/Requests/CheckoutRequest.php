<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'pelanggan';
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', Rule::in(['cod', 'transfer', 'qris'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
