<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Http\Requests\CashReplacementRequest;
use App\Http\Requests\CheckCashReplacementRequest;
use App\Http\Requests\CheckReplacementRequest;
use App\Models\Checks;
use App\Services\DatedPdcCheckServices;
use App\Services\DatedPdcDBServices;
use Illuminate\Http\Request;


class DatedPdcChecksController extends Controller
{
    public function __construct(public DatedPdcCheckServices $datedPdcChecks, public DatedPdcDBServices $dbservices)
    {
    }
    public function pdc_index(Request $request)
    {
        return inertia('Dated&PdcChecks/PDCChecks', [
            'data' => $this->datedPdcChecks->getPostDatedChecks($request),
            'filters' => $request->only(['search'])
        ]);
    }

    public function dated_index(Request $request)
    {
        return inertia('Dated&PdcChecks/DatedChecks', [
            'data' => $this->datedPdcChecks->getDatedCheckData($request),
            'columns' => ColumnsHelper::$dated_check_columns,
            'filters' => $request->only(['search'])
        ]);
    }

    public function pdc_cash_replacement(CashReplacementRequest $request)
    {
        $request->validated();

        $oldAmount = Checks::where('checks_id', $request->id)->first()->check_amount;

        $this->dbservices->pdcCashReplacement($request, $oldAmount);

        return redirect()->back();
    }

    public function pdc_check_replacement(CheckReplacementRequest $request)
    {

        $request->validated();

        $custId =  is_array($request->custName) ? $request->custName['value'] : $request->custName;

        $bankId =  is_array($request->bankName) ? $request->bankName['value'] : $request->bankName;

        $fromId =  is_array($request->checkFrom) ? $request->checkFrom['value'] : $request->checkFrom;

        $type = $request->checkDate > today()->toDateString() ? "POST DATED" : "DATED CHECK";

        return $this->dbservices->pdcCheckReplacement($request, $type, $custId, $bankId, $fromId);
    }

    public function pdc_check_cash_replacement(CheckCashReplacementRequest $request)
    {
        $request->validated();

        $type = self::getCheckType($request->checkDate);

        return $this->dbservices->pdcCheckCashreplacement($request, $type);
    }

    public function pdc_partial_replacement_cash(CashReplacementRequest $request)
    {
        $request->validated();

        return $this->dbservices->pdcPartialCashReplacement($request);
    }
    public function pdc_partial_replacement_check(CheckReplacementRequest $request)
    {
        $request->validated();

        $type = self::getCheckType($request->checkDate);

        return $this->dbservices->partialPdcCheckReplacement($request, $type);
    }

    public static function getCheckType($type)
    {
        return $type > today()->toDateString() ? "POST DATED" : "DATED CHECK";
    }
}
