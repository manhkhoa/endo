<?php

namespace App\Services\Company;

use App\Concerns\ItemImport;
use App\Imports\Company\BranchImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BranchImportService
{
    use ItemImport;

    public function import(Request $request)
    {
        $this->deleteLogFile('branch');

        $this->validateFile($request);

        Excel::import(new BranchImport, $request->file('file'));

        $this->reportError('branch');
    }
}
