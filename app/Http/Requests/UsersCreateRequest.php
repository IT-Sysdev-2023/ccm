<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
                'name' => ['required'],
                'empid' => ['required'],
                'username' => ['required'],
                'ContactNo' => ['required'],
                'company_id' => ['required'],
                'department_id' => ['required'],
                'usertype_id' => ['required'],
                'businessunit_id' => ['required'],
        ];
    }
}
