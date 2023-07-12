<?php

namespace App\Services\Company;

use App\Concerns\SubordinateAccess;
use App\Contracts\ListGenerator;
use App\Http\Resources\Company\DesignationResource;
use App\Models\Company\Designation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DesignationListService extends ListGenerator
{
    use SubordinateAccess;

    protected $allowedSorts = ['created_at', 'name', 'alias'];

    protected $defaultSort = 'name';

    protected $defaultOrder = 'asc';

    public function getHeaders(): array
    {
        $headers = [
            [
                'key' => 'name',
                'label' => trans('company.designation.props.name'),
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'alias',
                'label' => trans('company.designation.props.alias'),
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'parent',
                'label' => trans('company.designation.props.parent'),
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
        $query = Designation::query()
            ->with('parent')
            ->byTeam();

        $accessibleDesignationIds = $this->getAccessibleDesignation();

        if (is_array($accessibleDesignationIds)) {
            $query->whereIn('id', $accessibleDesignationIds);
        }

        return $query->filter([
            'App\QueryFilters\LikeMatch:name',
            'App\QueryFilters\LikeMatch:alias',
            'App\QueryFilters\UuidMatch',
        ]);
    }

    public function paginate(Request $request): AnonymousResourceCollection
    {
        return DesignationResource::collection($this->filter($request)
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
