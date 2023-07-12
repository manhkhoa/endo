<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Services\Company\BranchImportService;
use Illuminate\Http\Request;

class BranchImportController extends Controller
{
    public function __invoke(Request $request, BranchImportService $service)
    {
        $service->import($request);

        if (request()->boolean('validate')) {
            return response()->success([
                'message' => trans('general.data_validated'),
            ]);
        }

        return response()->success([
            'imported' => true,
            'message' => trans('global.imported', ['attribute' => trans('company.branch.branch')]),
        ]);
    }
}
