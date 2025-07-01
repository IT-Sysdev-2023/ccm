<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Http\Resources\NewDsCheckResource;
use App\Http\Resources\NewSavedCheckResource;
use App\Models\NewSavedChecks;
use App\Services\DsBounceTaggingService;

use Illuminate\Http\Request;


class DsBounceTaggingController extends Controller
{

    public function __construct(public DsBounceTaggingService $dsBounceTaggingService) {}
    public function indexBounceTagging(Request $request)
    {
        return inertia('Ds&BounceTagging/BounceTagging', [
            'data' => NewDsCheckResource::collection($this->dsBounceTaggingService->get_bounce_tagging($request)),
            'columns' => ColumnsHelper::$get_bounce_tagging_columns,
            'filters' => $request->only(['year', 'search']),
        ]);
    }
    public function indexDsTagging(Request $request)
    {
        return inertia('Ds&BounceTagging/DsTagging');
    }
    public function getDsTaggings(Request $request)
    {
        $data = $this->dsBounceTaggingService->indexDsTagging($request);

        return response()->json([
            'data' => NewSavedCheckResource::collection($data),
            'due_dates' => self::duedatesCounts($request),
            'total' => [
                'totalSum' => (float) $data->where('done', 'check')->sum('check_amount'),
                'count' => $data->where('done', 'check')->count(),
            ],
            'columns' => ColumnsHelper::$columns_ds_tagging,
        ]);
    }

    public function searchDsTagging(Request $request)
    {
        $data = $this->dsBounceTaggingService->searchDsTagging($request);

        return response()->json([
            'data' => NewSavedCheckResource::collection($data),
            'due_dates' => self::duedatesCounts($request),
            'total' => [
                'totalSum' => (float) $data->where('done', 'check')->sum('check_amount'),
                'count' => $data->where('done', 'check')->count(),
            ],
            'columns' => ColumnsHelper::$columns_ds_tagging,
        ]);
    }



    public static function duedatesCounts($request)
    {
        return NewSavedChecks::dsTaggingQuery($request->user()->businessunit_id)
            ->whereDate('checks.check_date', today()->toDateString())
            ->count();
    }

    public function updateSwitch(Request $request)
    {
        return $this->dsBounceTaggingService->updateSwitch($request);
    }

    public function get_bounce_tagging(Request $request)
    {
        return $this->dsBounceTaggingService->get_bounce_tagging($request);
    }

    public function tagCheckBounce(Request $request)
    {

        return $this->dsBounceTaggingService->tagCheckBounce($request);
    }

    public function submiCheckDs(Request $request)
    {
        $request->validate([
            'dsNo' => 'required',
            'dateDeposit' => 'required|date',
            'selected' => 'required|array|min:1',
        ]);

        if (empty($request->selected)) {
            return redirect()->back()->with([
                'status' => 'error',
                'title' => 'Please Select Atleast One Cheques'
            ]);
        }

        return $this->dsBounceTaggingService->submiCheckDs($request);
    }
}
