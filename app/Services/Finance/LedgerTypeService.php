<?php

namespace App\Services\Finance;

use App\Enums\Finance\LedgerGroup;
use App\Models\Finance\Ledger;
use App\Models\Finance\LedgerType;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LedgerTypeService
{
    public function preRequisite(Request $request): array
    {
        $ledgerGroups = LedgerGroup::getOptions();

        return compact('ledgerGroups');
    }

    public function create(Request $request): LedgerType
    {
        \DB::beginTransaction();

        $ledgerType = LedgerType::forceCreate($this->formatParams($request));

        \DB::commit();

        return $ledgerType;
    }

    private function formatParams(Request $request, ?LedgerType $ledgerType = null): array
    {
        $formatted = [
            'name' => $request->name,
            'alias' => $request->alias,
            'type' => $request->parent ? $request->parent_ledger_type : $request->type,
            'parent_id' => $request->parent_id,
            'description' => $request->description,
        ];

        if (! $ledgerType) {
            $formatted['team_id'] = session('team_id');
        }

        $meta = $ledgerType ? $ledgerType->meta : [];
        $meta['has_account'] = $request->has_account;
        $meta['has_contact'] = $request->has_contact;
        $formatted['meta'] = $meta;

        return $formatted;
    }

    private function ensureDoesntHaveLedgers(LedgerType $ledgerType): void
    {
        $ledgerExists = Ledger::whereLedgerTypeId($ledgerType->id)->exists();

        if ($ledgerExists) {
            throw ValidationException::withMessages(['message' => trans('global.associated_with_dependency', ['attribute' => trans('finance.ledger_type.ledger_type'), 'dependency' => trans('finance.ledger.ledger')])]);
        }
    }

    private function ensureChildDoesntHaveLedgers(LedgerType $ledgerType, $ids): void
    {
        $ledgerExists = Ledger::whereIn('ledger_type_id', $ids)->exists();

        if ($ledgerExists) {
            throw ValidationException::withMessages(['message' => trans('global.associated_with_dependency', ['attribute' => trans('finance.ledger_type.ledger_type'), 'dependency' => trans('finance.ledger.ledger')])]);
        }
    }

    public function update(Request $request, LedgerType $ledgerType): void
    {
        $children = $ledgerType->descendents();

        if (in_array($request->parent, $children->pluck('uuid')->all())) {
            throw ValidationException::withMessages(['message' => trans('global.child_cannot_become_parent', ['attribute' => trans('finance.ledger_type.ledger_type')])]);
        }

        $typeOrParentChange = false;
        if ($request->type != $ledgerType->type || $request->parent_id != $ledgerType->parent_id) {
            $typeOrParentChange = true;

            $this->ensureDoesntHaveLedgers($ledgerType);

            $this->ensureChildDoesntHaveLedgers($ledgerType, $children->pluck('id')->all());
        }

        \DB::beginTransaction();

        $ledgerType->forceFill($this->formatParams($request, $ledgerType))->save();

        if ($typeOrParentChange && $children->count()) {
            LedgerType::whereIn('id', $children->pluck('id')->all())->update(['type' => $ledgerType->type]);
        }

        \DB::commit();
    }

    public function deletable(LedgerType $ledgerType): void
    {
        $parentExists = LedgerType::whereParentId($ledgerType->id)->exists();

        if ($parentExists) {
            throw ValidationException::withMessages(['message' => trans('global.associated_with_parent_dependency', ['attribute' => trans('finance.ledger_type.ledger_type')])]);
        }

        $this->ensureDoesntHaveLedgers($ledgerType);

        $children = $ledgerType->descendents();

        $this->ensureChildDoesntHaveLedgers($ledgerType, $children->pluck('id')->all());
    }
}
