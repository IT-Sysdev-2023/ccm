<?php

namespace App\Http\Resources;

use App\Helper\NumberHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;

class NewDsCheckResource extends JsonResource
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
            'ds_no' => $this->ds_no,
            'check_received' => Date::parse($this->check->check_received)->toFormattedDateString(),
            'check_date' => Date::parse($this->check->check_date)->toFormattedDateString(),
            'date_deposit' => Date::parse($this->check->date_deposit)->toFormattedDateString(),
            'check_amount' => $this->check->check_amount,
            'check_no' => $this->check->check_no,
            'fullname' => $this->check->customer->fullname,
        ];
    }
}
