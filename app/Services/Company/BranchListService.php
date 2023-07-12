<?php

namespace App\Services\Company;

use App\Concerns\SubordinateAccess;
use App\Contracts\ListGenerator;
use App\Http\Resources\Company\BranchResource;
use App\Models\Company\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BranchListService extends ListGenerator
{
    use SubordinateAccess;

    protected $allowedSorts = ['created_at', 'name', 'alias', 'code'];

    protected $defaultSort = 'name';

    protected $defaultOrder = 'asc';

    public function getHeaders(): array
    {
        $headers = [
            [
                'key' => 'name',
                'label' => trans('company.branch.props.name'),
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'code',
                'label' => trans('company.branch.props.code'),
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'alias',
                'label' => trans('company.branch.props.alias'),
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'parent',
                'label' => trans('company.branch.props.parent'),
                'print_label' => 'parent.name',
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

    public function filter(Request $request): Builder
    {
        $query = Branch::query()
            ->with('parent')
            ->byTeam();

        $accessibleBranchIds = $this->getAccessibleBranch();

        if (is_array($accessibleBranchIds)) {
            $query->whereIn('id', $accessibleBranchIds);
        }

        return $query->filter([
            'App\QueryFilters\LikeMatch:name',
            'App\QueryFilters\LikeMatch:alias',
            'App\QueryFilters\LikeMatch:code',
            'App\QueryFilters\UuidMatch',
        ]);
    }

    public function paginate(Request $request): AnonymousResourceCollection
    {
        return BranchResource::collection($this->filter($request)
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

    public function list(Request $request): AnonymousResourceCollection
    {
        return $this->paginate($request);
    }
}
