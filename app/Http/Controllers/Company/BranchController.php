<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\BranchRequest;
use App\Http\Resources\Company\BranchResource;
use App\Models\Company\Branch;
use App\Services\Company\BranchListService;
use App\Services\Company\BranchService;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('test.mode.restriction')->only(['destroy']);
    }

    public function index(Request $request, BranchListService $service)
    {
        $this->authorize('viewAny', Branch::class);

        return $service->paginate($request);
    }

    public function store(BranchRequest $request, BranchService $service)
    {
        $this->authorize('create', Branch::class);

        $branch = $service->create($request);

        return response()->success([
            'message' => trans('global.created', ['attribute' => trans('company.branch.branch')]),
            'branch' => BranchResource::make($branch),
        ]);
    }

    public function show(Branch $branch, BranchService $service)
    {
        $this->authorize('view', $branch);

        $branch->load('parent');

        return BranchResource::make($branch);
    }

    public function update(BranchRequest $request, Branch $branch, BranchService $service)
    {
        $this->authorize('update', $branch);

        $service->update($request, $branch);

        return response()->success([
            'message' => trans('global.updated', ['attribute' => trans('company.branch.branch')]),
        ]);
    }

    public function destroy(Branch $branch, BranchService $service)
    {
        $this->authorize('delete', $branch);

        $service->deletable($branch);

        $branch->delete();

        return response()->success([
            'message' => trans('global.deleted', ['attribute' => trans('company.branch.branch')]),
        ]);
    }
}
