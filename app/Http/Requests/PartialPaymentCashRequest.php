<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PartialPaymentCashRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
            'penalty' => 'required|numeric',
            'amount' => 'required|',
            'ards' => 'required|string',
            'reason' => 'required|string',
            'date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'penalty.required' => 'The cash penalty field is required.',
            'penalty.numeric' => 'The cash penalty must be a number.',
            'ards.required' => 'The AR DS field is required.',
            'ards.string' => 'The AR DS must be a string.',
            'reason.required' => 'The reason field is required.',
            'reason.string' => 'The reason must be a string.',
            'date.required' => 'The date field is required.',
            'date.date' => 'The date must be a valid date format.',
            'amount.required' => 'The Check Amount Field is Required.',
        ];
    }
}
