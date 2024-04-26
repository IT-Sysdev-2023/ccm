<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MergeCheckStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'accountnumber' => 'required',
            'checkdate' => 'required|date',
            'currency' => 'required',
            'checkfrom' => 'required',
            'accountname' => 'required',
            'customer' => 'required',
            'checkamount' => 'required|numeric',
            'checkclass' => 'required',
            'checknumber' => 'required',
            'bankname' => 'required',
            'checkreceived' => 'required|date',
            'checkcategory' => 'required',
            'approvingOfficer' => 'required',
            'penalty' => 'required|numeric',
            'reason' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'accountnumber.required' => 'The account number field is required.',
            'checkdate.required' => 'The check date field is required.',
            'checkdate.date' => 'The check date must be a valid date format.',
            'currency.required' => 'The currency field is required.',
            'checkfrom.required' => 'The check from field is required.',
            'accountname.required' => 'The account name field is required.',
            'customer.required' => 'The customer field is required.',
            'checkamount.required' => 'The check amount field is required.',
            'checkamount.numeric' => 'The check amount must be a numeric value.',
            'checkclass.required' => 'The check class field is required.',
            'checknumber.required' => 'The check number field is required.',
            'bankname.required' => 'The bank name field is required.',
            'checkreceived.required' => 'The check received field is required.',
            'checkreceived.date' => 'The check received is required.',
            'checkcategory.required' => 'The check category field is required.',
            'approvingOfficer.required' => 'The approving officer field is required.',
            'penalty.required' => 'The penalty field is required.',
            'penalty.numeric' => 'The penalty should be a number',
            'reason.required' => 'The reason field is required.',
        ];
    }
}
