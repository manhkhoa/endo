<?php

namespace App\Services\Employee;

use App\Contracts\ListGenerator;
use App\Http\Resources\Employee\QualificationResource;
use App\Models\Employee\Employee;
use App\Models\Employee\Qualification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QualificationListService extends ListGenerator
{
    protected $allowedSorts = ['created_at'];

    protected $defaultSort = 'created_at';

    public function getHeaders(): array
    {
        $headers = [
            [
                'key' => 'course',
                'label' => trans('employee.qualification.props.course'),
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'institute',
                'label' => trans('employee.qualification.props.institute'),
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'level',
                'label' => trans('employee.qualification_level.qualification_level'),
                'print_label' => 'level.name',
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'startDate',
                'label' => trans('employee.qualification.props.start_date'),
                'print_label' => 'start_date_display',
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'endDate',
                'label' => trans('employee.qualification.props.end_date'),
                'print_label' => 'end_date_display',
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'result',
                'label' => trans('employee.qualification.props.result'),
                'sortable' => false,
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

    public function filter(Request $request, Employee $employee): Builder
    {
        return Qualification::query()
            ->with('level')
            ->whereEmployeeId($employee->id)
            ->filter([
                'App\QueryFilters\LikeMatch:course',
                'App\QueryFilters\LikeMatch:institute',
                'App\QueryFilters\LikeMatch:affiliated_to',
                'App\QueryFilters\DateBetween:start_date,end_date,start_date,end_date',
            ]);
    }

    public function paginate(Request $request, Employee $employee): AnonymousResourceCollection
    {
        return QualificationResource::collection($this->filter($request, $employee)
                ->orderBy($this->getSort(), $this->getOrder())
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

    public function list(Request $request, Employee $employee): AnonymousResourceCollection
    {
        return $this->paginate($request, $employee);
    }
}
