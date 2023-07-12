<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Services\Company\DesignationImportService;
use Illuminate\Http\Request;

class DesignationImportController extends Controller
{
    public function __invoke(Request $request, DesignationImportService $service)
    {
        $service->import($request);

        if (request()->boolean('validate')) {
            return response()->success([
                'message' => trans('general.data_validated'),
            ]);
        }

        return response()->success([
            'imported' => true,
            'message' => trans('global.imported', ['attribute' => trans('company.designation.designation')]),
        ]);
    }
}
