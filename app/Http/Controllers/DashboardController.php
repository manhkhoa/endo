<?php

namespace App\Http\Controllers;

use App\Services\Dashboard\ChartService;
use App\Services\Dashboard\FavoriteService;
use App\Services\Dashboard\RecordService;
use App\Services\Dashboard\StatService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard stats
     */
    public function stat(Request $request, StatService $service)
    {
        return $service->getData($request);
    }

    public function favorite(Request $request, FavoriteService $service)
    {
        return $service->getData($request);
    }

    public function chart(Request $request, ChartService $service)
    {
        return $service->getData($request);
    }

    public function record(Request $request, RecordService $service)
    {
        return $service->getData($request);
    }
}
