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
            "account" => 'required',
            "accName" => 'required',
            "checkAmount" => 'required',
            "penAmount" => 'required',
            "appOfficer" => 'required',
            "checkNo" => 'required',
            "checkDate" => 'required|date',
            "checkRec" => 'required|date',
            "checkFrom" => 'required',
            "checkCat" => 'required',
            "checkClass" => 'required',
            "bankName" => 'required',
            "currency" => 'required',
            "custName" => 'required',
            "reason" => 'required',
            "repDate" => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'checkFrom.required' => 'The check from field is required.',
            'bankName.required' => 'The bank name field is required.',
            'custName.required' => 'The customer name field is required.',
            'appOfficer.required' => 'The approving officer field is required.',
            'currency.required' => 'The currency field is required.',
            'checkCat.required' => 'The category field is required.',
            'reason.required' => 'The reason field is required.',
            'checkClass.required' => 'The check class field is required.',
            'repDate.required' => 'The replace date field is required.',
            'repDate.date' => 'The replace date field is required.',
            'checkDate.required' => 'The check date field is required.',
            'checkDate.date' => 'The check date field must be a valid date format.',
            'checkRec.required' => 'The check recieved field is required.',
            'checkRec.date' => 'The check recieved field must be a valid date format.',
            'penAmount.required' => 'The penalty field is required.',
            'penAmount.numeric' => 'The penalty field must be a number.',
            'accName.required' => 'The account name field is required.',
            'account.required' => 'The account number field is required.',
            'checkNo.required' => 'The check number field is required.',
        ];
    }
}
