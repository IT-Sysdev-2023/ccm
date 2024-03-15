<?php

namespace App\Http\Controllers;

use App\Helper\ColumnsHelper;
use App\Helper\NumberHelper;
use App\Models\NewBounceCheck;
use App\Models\CheckHistory;
use App\Models\Checks;
use App\Models\NewDsChecks;
use App\Models\NewSavedChecks;
use App\Services\DsBounceTaggingService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;
use Inertia\Inertia;

class DsBounceTaggingController extends Controller
{

    public function __construct(public DsBounceTaggingService $dsBounceTaggingService)
    {
    }
    public function indexBounceTagging(Request $request)
    {

        return $this->dsBounceTaggingService->get_bounce_tagging($request);
    }
    public function updateSwitch(Request $request)
    {
        return $this->dsBounceTaggingService->updateSwitch($request);
    }
    public function indexDsTagging(Request $request)
    {
        return $this->dsBounceTaggingService->indexDsTagging($request);
    }

    public function get_bounce_tagging(Request $request)
    {
        // dd(1);
        return $this->dsBounceTaggingService->get_bounce_tagging($request);
    }

    public function tag_check_bounce(Request $request)
    {

        return $this->dsBounceTaggingService->tag_check_bounce($request);

    }

    public function dummy()
    {
        $dummy = Checks::with('checkreceived')->get();
    }

    public function submiCheckDs(Request $request)
    {
        return $this->dsBounceTaggingService->submiCheckDs($request);
    }

}
