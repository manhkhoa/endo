<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Services\Company\DesignationListService;
use Illuminate\Http\Request;

class DesignationExportController extends Controller
{
    public function __invoke(Request $request, DesignationListService $service)
    {
        $list = $service->list($request);

        return $service->export($list);
    }
}
