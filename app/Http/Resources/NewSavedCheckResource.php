<?php

namespace App\Http\Resources;

use App\Helper\NumberHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;

class NewSavedCheckResource extends JsonResource
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
            'type' => Date::parse($this->check_date)->lessThanOrEqualTo(today()) ? 'DATED' : 'POST-DATED',
            'done' => empty($this->done) ? false : true,
            'check_received' => Date::parse($this->check_received)->toFormattedDateString(),
            'check_date' => Date::parse($this->check_date)->toFormattedDateString(),
            'check_amount' => NumberHelper::float($this->check_amount),
            'check_category' => $this->check_category,
            'check_no' => $this->check_no,
            'fullname' => $this->fullname,
        ];
    }

}
