<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartialPaymentRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'parArDs' => 'required',
            'parReason' => 'required',
            'parRepDate' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'parArDs.required' => 'The Ar Ds field is required',
            'parReason.required' => 'The Reason field is required',
            'parRepDate.required' => 'The Replacement date field is required',
            'parRepDate.date' => 'The Replacement date field is required',
        ];
    }
}
