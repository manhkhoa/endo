<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\LedgerTypeRequest;
use App\Http\Resources\Finance\LedgerTypeResource;
use App\Models\Finance\LedgerType;
use App\Services\Finance\LedgerTypeListService;
use App\Services\Finance\LedgerTypeService;
use Illuminate\Http\Request;

class LedgerTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('test.mode.restriction')->only(['destroy']);
    }

    public function preRequisite(Request $request, LedgerTypeService $service)
    {
        return response()->ok($service->preRequisite($request));
    }

    public function index(Request $request, LedgerTypeListService $service)
    {
        return $service->paginate($request);
    }

    public function store(LedgerTypeRequest $request, LedgerTypeService $service)
    {
        $ledgerType = $service->create($request);

        return response()->success([
            'message' => trans('global.created', ['attribute' => trans('finance.ledger_type.ledger_type')]),
            'ledger_type' => LedgerTypeResource::make($ledgerType),
        ]);
    }

    public function show(LedgerType $ledgerType, LedgerTypeService $service)
    {
        $ledgerType->load('parent');

        return LedgerTypeResource::make($ledgerType);
    }

    public function update(LedgerTypeRequest $request, LedgerType $ledgerType, LedgerTypeService $service)
    {
        $service->update($request, $ledgerType);

        return response()->success([
            'message' => trans('global.updated', ['attribute' => trans('finance.ledger_type.ledger_type')]),
        ]);
    }

    public function destroy(LedgerType $ledgerType, LedgerTypeService $service)
    {
        $service->deletable($ledgerType);

        $ledgerType->delete();

        return response()->success([
            'message' => trans('global.deleted', ['attribute' => trans('finance.ledger_type.ledger_type')]),
        ]);
    }
}
