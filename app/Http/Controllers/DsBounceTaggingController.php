<?php

namespace App\Http\Controllers;

use App\Services\DsBounceTaggingService;

use Illuminate\Http\Request;


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
        // dd(1);
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

    public function tagCheckBounce(Request $request)
    {

        return $this->dsBounceTaggingService->tagCheckBounce($request);

    }


    public function submiCheckDs(Request $request)
    {
        return $this->dsBounceTaggingService->submiCheckDs($request);
    }

}
