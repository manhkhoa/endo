<?php

namespace App\Services\Employee;

use App\Contracts\ListGenerator;
use App\Http\Resources\Employee\AccountResource;
use App\Models\Account;
use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AccountListService extends ListGenerator
{
    protected $allowedSorts = ['created_at'];

    protected $defaultSort = 'created_at';

    public function getHeaders(): array
    {
        $headers = [
            [
                'key' => 'name',
                'label' => trans('finance.account.props.name'),
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'alias',
                'label' => trans('finance.account.props.alias'),
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'number',
                'label' => trans('finance.account.props.number'),
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'bankName',
                'label' => trans('finance.account.props.bank_name'),
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'branchName',
                'label' => trans('finance.account.props.branch_name'),
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
        return Account::query()
            ->whereHasMorph(
                'accountable', [Employee::class],
                function ($q) use ($employee) {
                    $q->whereId($employee->id);
                }
            )->filter([
                'App\QueryFilters\LikeMatch:name',
                'App\QueryFilters\LikeMatch:alias',
                'App\QueryFilters\LikeMatch:number',
            ]);
    }

    public function paginate(Request $request, Employee $employee): AnonymousResourceCollection
    {
        return AccountResource::collection($this->filter($request, $employee)
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
