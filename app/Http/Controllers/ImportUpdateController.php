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

        return Inertia::render('Updates&Import/InstitutionalUpdateChecks', [
            'qoute_api' => $quoteApi,
            'count' => $tfCounts
        ]);
    }
    public function startImportChecks()
    {
        return (new ImportUpdateService())->importResult();
    }
}
