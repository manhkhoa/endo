<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\DepartmentRequest;
use App\Http\Resources\Company\DepartmentResource;
use App\Models\Company\Department;
use App\Services\Company\DepartmentListService;
use App\Services\Company\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('test.mode.restriction')->only(['destroy']);
    }

    public function index(Request $request, DepartmentListService $service)
    {
        $this->authorize('viewAny', Department::class);

        return $service->paginate($request);
    }

    public function store(DepartmentRequest $request, DepartmentService $service)
    {
        $this->authorize('create', Department::class);

        $department = $service->create($request);

        return response()->success([
            'message' => trans('global.created', ['attribute' => trans('company.department.department')]),
            'department' => DepartmentResource::make($department),
        ]);
    }

    public function show(Department $department, DepartmentService $service)
    {
        $this->authorize('view', $department);

        return DepartmentResource::make($department);
    }

    public function update(DepartmentRequest $request, Department $department, DepartmentService $service)
    {
        $this->authorize('update', $department);

        $service->update($request, $department);

        return response()->success([
            'message' => trans('global.updated', ['attribute' => trans('company.department.department')]),
        ]);
    }

    public function destroy(Department $department, DepartmentService $service)
    {
        $this->authorize('delete', $department);

        $service->deletable($department);

        $department->delete();

        return response()->success([
            'message' => trans('global.deleted', ['attribute' => trans('company.department.department')]),
        ]);
    }
}
