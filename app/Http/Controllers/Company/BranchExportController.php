<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Services\Company\BranchListService;
use Illuminate\Http\Request;

class BranchExportController extends Controller
{
    public function __invoke(Request $request, BranchListService $service)
    {
        $list = $service->list($request);

        return $service->export($list);
    }
}
