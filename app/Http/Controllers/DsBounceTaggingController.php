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

    public function __construct(public DsBounceTaggingService $dsBounceTaggingService)
    {
    }
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
        $data = $this->dsBounceTaggingService->indexDsTagging($request);

        return inertia('Ds&BounceTagging/DsTagging', [
            'due_dates' => self::duedatesCounts($request),
            'total' => [
                'totalSum' => (float) $data->sum('check_amount'),
                'count' => $data->count(),
            ],
            'data' => NewSavedCheckResource::collection($data->paginate(10)->withQueryString()),
            'columns' => ColumnsHelper::$columns_ds_tagging,
            'filters' => $request->only(['search']),
            'tab' => $request->tab ?? '1',
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
        return $this->dsBounceTaggingService->submiCheckDs($request);
    }
}
