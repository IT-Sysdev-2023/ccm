<?php

namespace App\Http\Controllers;


use App\Services\ImportUpdateService;
use Inertia\Inertia;
use Illuminate\Support\Facades\File;
use App\Traits\ImportUpateTraits;

class ImportUpdateController extends Controller
{
    use ImportUpateTraits;
    public function indeximportupdates()
    {
        $quoteApi = config('app.qoute_api');
        $directory = 'C:\Users\Programming\Documents\CCM_Textfile\files';

        $files = File::allFiles($directory);


        return Inertia::render('Updates&Import/InstitutionalUpdateChecks', [
            'qoute_api' => $quoteApi,
            'data' => $files
        ]);
    }
    public function startImportChecks()
    {


        $directory = 'C:\Users\Programming\Documents\CCM_Textfile\files';

        $files = File::allFiles($directory);

        $tfCounts = count($files);

        return(new ImportUpdateService())
            ->record($files)
            ->importResult($tfCounts);

    }
}
