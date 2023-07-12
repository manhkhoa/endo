<?php

namespace App\Services\Employee;

use App\Concerns\SubordinateAccess;
use App\Contracts\ListGenerator;
use App\Http\Resources\Employee\EmployeeListResource;
use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class EmployeeListService extends ListGenerator
{
    use SubordinateAccess;

    protected $allowedSorts = ['created_at', 'name', 'code_number', 'joining_date', 'employment_status', 'department', 'designation', 'branch'];

    protected $defaultSort = 'created_at';

    protected $defaultOrder = 'asc';

    public function getHeaders(): array
    {
        $headers = [
            [
                'key' => 'codeNumber',
                'label' => trans('employee.props.code_number'),
                'print_label' => 'code_number',
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'name',
                'label' => trans('employee.props.name'),
                'print_label' => 'full_name',
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'joiningDate',
                'label' => trans('employee.props.joining_date'),
                'print_label' => 'joining_date',
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'employmentStatus',
                'label' => trans('employee.employment_status.employment_status'),
                'print_label' => 'employment_status',
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'department',
                'label' => trans('company.department.department'),
                'print_label' => 'department',
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'designation',
                'label' => trans('company.designation.designation'),
                'print_label' => 'designation',
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'branch',
                'label' => trans('company.branch.branch'),
                'print_label' => 'branch',
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'createdAt',
                'label' => trans('general.created_at'),
                'sortable' => true,
                'visibility' => true,
            ],
        ];

        if (request()->ajax()) {
            $headers[] = $this->actionHeader;
        }

        return $headers;
    }

    public function filter(Request $request): Builder
    {
        $uuid = Str::toArray($request->query('uuid'));
        $name = $request->query('name');
        $status = $request->query('status');
        $self = $request->query('self', 1);
        $employmentStatus = Str::toArray($request->query('employment_status'));

        $employee = Employee::withAuthRecordId();
        $accessibleBranch = $this->accessibleBranchRules($employee);
        $accessibleDesignation = $this->accessibleDesignationRules($employee);

        $date = today()->toDateString();

        return Employee::detail()->filterRecord()
            ->ignoreSelf($self, $employee->id)
            ->when($name, function ($q, $name) {
                if (app()->environment('testing')) {
                    $q->where('first_name', 'LIKE', '%'.$name.'%');
                } else {
                    $q->where(\DB::raw('(SELECT concat_ws(" ", first_name,middle_name,third_name,last_name))'), 'LIKE', '%'.$name.'%');
                }
            })
            ->when($status == 'active', function ($q) use ($date) {
                $q->where(function ($q) use ($date) {
                    $q->whereNull('leaving_date')->orWhere('leaving_date', '>=', $date);
                });
            })
            ->when($status == 'inactive', function ($q) use ($date) {
                $q->whereNotNull('leaving_date')->where('leaving_date', '<', $date);
            })
            ->filterDesignation($accessibleDesignation, $employee->id)
            ->filterBranch($accessibleBranch, $employee->id)
            ->filter([
                'App\QueryFilters\WhereInMatch:employees.uuid,uuid',
                'App\QueryFilters\WhereInMatch:departments.uuid,department',
                'App\QueryFilters\WhereInMatch:designations.uuid,designation',
                'App\QueryFilters\WhereInMatch:branches.uuid,branch',
                'App\QueryFilters\WhereInMatch:options.uuid,employment_status',
                'App\QueryFilters\ExactMatch:code_number',
                'App\QueryFilters\DateBetween:joining_start_date,joining_end_date,joining_date',
                'App\QueryFilters\DateBetween:leaving_start_date,leaving_end_date,leaving_date',
            ]);
    }

    public function paginate(Request $request): AnonymousResourceCollection
    {
        $view = $request->query('view', 'card');
        $request->merge(['view' => $view]);

        $query = $this->filter($request);

        if ($this->getSort() == 'code_number') {
            $query->orderBy('code_number', $this->getOrder());
        } elseif ($this->getSort() == 'name') {
            $query->orderBy('full_name', $this->getOrder());
        } elseif ($this->getSort() == 'employment_status') {
            $query->orderBy('options.name', $this->getOrder());
        } elseif ($this->getSort() == 'department') {
            $query->orderBy('departments.name', $this->getOrder());
        } elseif ($this->getSort() == 'designation') {
            $query->orderBy('designations.name', $this->getOrder());
        } elseif ($this->getSort() == 'branch') {
            $query->orderBy('branches.name', $this->getOrder());
        } elseif ($this->getSort() == 'created_at') {
            $query->orderBy('employees.created_at', $this->getOrder());
        } else {
            $query->orderBy($this->getSort(), $this->getOrder());
        }

        return EmployeeListResource::collection($query
                ->paginate((int) $this->getPageLength(), ['*'], 'current_page'))
        ->additional([
            'headers' => $this->getHeaders(),
            'meta' => [
                'allowed_sorts' => $this->allowedSorts,
                'default_sort' => $this->defaultSort,
                'default_order' => $this->defaultOrder,
            ],
        ]);
    }

    public function list(Request $request): AnonymousResourceCollection
    {
        return $this->paginate($request);
    }
}
