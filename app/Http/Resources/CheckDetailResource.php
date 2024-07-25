<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;

class CheckDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'checks_id' => $this->checks_id,
            'check_no' => $this->check_no,
            'check_class' => $this->check_class,
            'check_category' => $this->check_category,
            'check_date' => Date::parse($this->check_date)->toFormattedDateString(),
            'check_received' => Date::parse($this->check_received)->toFormattedDateString(),
            'check_type' => $this->check_type,
            'approving_officer' => $this->approving_officer,
            'check_amount' => $this->check_amount,
            'check_status' => $this->check_status,
            'fullname' => $this->customer->fullname,
            'bankbranchname' => $this->bank->bankbranchname,
            'department' => $this->department->department,
            'account_no' => $this->account_no,
            'account_name' => $this->account_name,
        ];

    }
}
