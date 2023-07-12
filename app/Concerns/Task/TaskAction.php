<?php

namespace App\Concerns\Task;

use Illuminate\Support\Arr;

trait TaskAction
{
    public function isActionable(): bool
    {
        if ($this->cancelled_at) {
            return false;
        }

        if ($this->is_completed) {
            return false;
        }

        if (! $this->owner_id) {
            return false;
        }

        return true;
    }

    public function isEditable(): bool
    {
        if ($this->cancelled_at) {
            return false;
        }

        if ($this->is_completed) {
            return false;
        }

        if ($this->archived_at) {
            return false;
        }

        return true;
    }

    public function isDeletable(): bool
    {
        if (! $this->owner_id) {
            return false;
        }

        return true;
    }

    public function canManage(string $permission): bool
    {
        if ($this->cancelled_at) {
            return false;
        }

        if ($this->completed_at) {
            return false;
        }

        if ($this->is_owner) {
            return true;
        }

        if (! $this->is_member) {
            return false;
        }

        $meta = json_decode($this->member_meta, true);

        if (! (bool) Arr::get($meta, 'permission.manage_'.$permission, false)) {
            return false;
        }

        return true;
    }

    public function canMarkAsComplete(): bool
    {
        $meta = json_decode($this->member_meta, true);

        if (! $this->is_owner && ! (bool) Arr::get($meta, 'permission.manage_completion', false)) {
            return false;
        }

        if ($this->cancelled_at) {
            return false;
        }

        if ($this->completed_at) {
            return false;
        }

        if (! $this->relationLoaded('checklists')) {
            return false;
        }

        if ($this->checklists()->count() && $this->checklists()->where('completed_at', null)->count()) {
            return false;
        }

        return true;
    }

    public function canMarkAsIncomplete(): bool
    {
        $meta = json_decode($this->member_meta, true);

        if (! $this->is_owner && ! (bool) Arr::get($meta, 'permission.manage_completion', false)) {
            return false;
        }

        if ($this->cancelled_at) {
            return false;
        }

        if ($this->archived_at) {
            return false;
        }

        if (! $this->completed_at) {
            return false;
        }

        return true;
    }

    public function canMarkAsCancel(): bool
    {
        if (! $this->is_owner) {
            return false;
        }

        if ($this->cancelled_at) {
            return false;
        }

        if ($this->completed_at) {
            return false;
        }

        return true;
    }

    public function canMarkAsActive(): bool
    {
        if (! $this->is_owner) {
            return false;
        }

        if (! $this->cancelled_at) {
            return false;
        }

        if ($this->completed_at) {
            return false;
        }

        return true;
    }

    public function canMoveToArchive(): bool
    {
        if (! $this->is_owner) {
            return false;
        }

        if ($this->archived_at) {
            return false;
        }

        if (! $this->cancelled_at && ! $this->completed_at) {
            return false;
        }

        return true;
    }

    public function canMoveFromArchive(): bool
    {
        if (! $this->is_owner) {
            return false;
        }

        if (! $this->archived_at) {
            return false;
        }

        return true;
    }

    public function canToggleFavorite(): bool
    {
        if (! $this->is_owner && ! $this->is_member) {
            return false;
        }

        return true;
    }
}
