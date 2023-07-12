<?php

namespace App\Services\Company;

use App\Concerns\ItemImport;
use App\Imports\Company\DepartmentImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DepartmentImportService
{
    use ItemImport;

    public function import(Request $request)
    {
        $this->deleteLogFile('department');

        $this->validateFile($request);

        Excel::import(new DepartmentImport, $request->file('file'));

        $this->reportError('department');
    }
}
