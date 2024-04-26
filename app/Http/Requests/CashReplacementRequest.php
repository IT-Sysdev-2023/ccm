<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CashReplacementRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rep_cash_penalty' => 'required|numeric',
            'rep_ar_ds' => 'required|string',
            'rep_reason' => 'required|string',
            'rep_date' => 'required|date'
        ];
    }

    public function messages(): array
    {
        return [
            'rep_cash_penalty.required' => 'The cash penalty field is required.',
            'rep_cash_penalty.numeric' => 'The cash penalty must be a number.',
            'rep_ar_ds.required' => 'The AR DS field is required.',
            'rep_ar_ds.string' => 'The AR DS must be a string.',
            'rep_reason.required' => 'The reason field is required.',
            'rep_reason.string' => 'The reason must be a string.',
            'rep_date.required' => 'The date field is required.',
            'rep_date.date' => 'The date must be a valid date format.'
        ];
    }
}
