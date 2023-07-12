<?php

namespace App\Services\Finance;

use App\Contracts\ListGenerator;
use App\Http\Resources\Finance\TransactionResource;
use App\Models\Finance\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class TransactionListService extends ListGenerator
{
    protected $allowedSorts = ['created_at', 'date', 'amount'];

    protected $defaultSort = 'date';

    protected $defaultOrder = 'desc';

    public function getHeaders(): array
    {
        $headers = [
            [
                'key' => 'codeNumber',
                'label' => trans('finance.transaction.props.code_number'),
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'date',
                'label' => trans('finance.transaction.props.date'),
                'print_label' => 'date_display',
                'sortable' => true,
                'visibility' => true,
            ],
            [
                'key' => 'type',
                'label' => trans('finance.transaction.props.type'),
                'print_label' => 'type_display',
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'ledger',
                'label' => trans('finance.ledger.ledger'),
                'print_label' => 'ledger.name',
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'secondaryLedger',
                'label' => trans('finance.ledger.secondary_ledger'),
                'print_label' => 'record.ledger.name',
                'sortable' => false,
                'visibility' => true,
            ],
            [
                'key' => 'amount',
                'label' => trans('finance.transaction.props.amount'),
                'print_label' => 'amount_display',
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
        $secondaryLedgers = Str::toArray($request->query('secondary_ledgers'));

        return Transaction::query()
            ->with('ledger')
            ->withRecord()
            ->byTeam()
            ->when($request->boolean('show_cancelled'), function ($q) {
                $q->whereNotNull('cancelled_at');
            })
            ->when(! $request->boolean('show_cancelled'), function ($q) {
                $q->whereNull('cancelled_at');
            })
            ->when($secondaryLedgers, function ($q) use ($secondaryLedgers) {
                $q->whereHas('records', function ($q) use ($secondaryLedgers) {
                    $q->whereIn('ledger_id', $secondaryLedgers);
                });
            })
            ->filter([
                'App\QueryFilters\UuidMatch',
                'App\QueryFilters\DateBetween:start_date,end_date,date',
                'App\QueryFilters\WhereInMatch:ledgers.uuid,ledger',
            ]);
    }

    public function paginate(Request $request): AnonymousResourceCollection
    {
        return TransactionResource::collection($this->filter($request)
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
