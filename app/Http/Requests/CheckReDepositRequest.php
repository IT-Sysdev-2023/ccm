<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckReDepositRequest extends FormRequest
{
   

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rep_date' => 'required|date',
            'rep_penalty' => 'required|numeric',
            'rep_reason' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'rep_date.required' => 'The replacement date is required',
            'rep_date.date' => 'The replacement date must ba a valid date',
            'rep_penalty.required' => 'The penalty is required',
            'rep_penalty.numeric' => 'The penalty must be a number',
            'rep_reason.required' => 'The reason is required',
        ];
    }
}
