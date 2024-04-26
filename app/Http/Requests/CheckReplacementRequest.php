<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckReplacementRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'checkFrom_id' => 'required',
            'bank_id' => 'required',
            'customerId' => 'required',
            'approvingOfficer' => 'required',
            'currency_id' => 'required',
            'category' => 'required',
            'rep_reason' => 'required',
            'checkClass' => 'required',
            'rep_date' => 'required|date',
            'rep_check_date' => 'required|date',
            'rep_check_received' => 'required|date',
            'rep_check_penalty' => 'required',
            'accountname' => 'required',
            'accountnumber' => 'required',
            'checkNumber' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'checkFrom_id.required' => 'The check from field is required.',
            'bank_id.required' => 'The bank name field is required.',
            'customerId.required' => 'The customer name field is required.',
            'approvingOfficer.required' => 'The approving officer field is required.',
            'currency_id.required' => 'The currency field is required.',
            'category.required' => 'The category field is required.',
            'rep_reason.required' => 'The reason field is required.',
            'checkClass.required' => 'The check class field is required.',
            'rep_date.required' => 'The replace date field is required.',
            'rep_date.date' => 'The replace date field is required.',
            'rep_check_date.required' => 'The check date field is required.',
            'rep_check_date.date' => 'The check date field must be a valid date format.',
            'rep_check_received.required' => 'The check recieved field is required.',
            'rep_check_received.date' => 'The check recieved field must be a valid date format.',
            'rep_check_penalty.required' => 'The penalty field is required.',
            'rep_check_penalty.numeric' => 'The penalty field must be a number.',
            'accountname.required' => 'The account name field is required.',
            'accountnumber.required' => 'The account number field is required.',
            'checkNumber.required' => 'The check number field is required.',
        ];
    }
}
