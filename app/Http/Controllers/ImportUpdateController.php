<?php

namespace App\Http\Controllers;


use App\Services\ImportUpdateService;
use Inertia\Inertia;
use App\Traits\ImportUpateTraits;

class ImportUpdateController extends Controller
{
    use ImportUpateTraits;
    public function indeximportupdates()
    {
        $quoteApi = config('app.qoute_api');

        $tfCounts = $this->importInstitutionalIpAddress()->tfCounts;

        $atpCountData = count($this->checkEncashData());

        // dd($atpCountData);

        return Inertia::render('Updates&Import/InstitutionalUpdateChecks', [
            'qoute_api' => $quoteApi,
            'count' => $tfCounts,
            'countAtp' =>  $atpCountData
        ]);
    }
    public function startImportChecks()
    {
        return (new ImportUpdateService())->importResult();
    }

    public function updateAtpDatabase()
    {

        return (new ImportUpdateService())->updateResult();
    }
}
