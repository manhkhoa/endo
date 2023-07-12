<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Models\Contact;
use App\Models\Finance\Transaction;
use App\Models\Team\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role as SpatieRole;

class UserService
{
    public function preRequisite(): array
    {
        $statuses = UserStatus::getOptions();

        $roles = Role::selectOption();

        return compact('statuses', 'roles');
    }

    public function create(Request $request): User
    {
        \DB::beginTransaction();

        $user = User::forceCreate($this->formatParams($request));

        $user->assignRole(SpatieRole::find($request->role_ids));

        \DB::commit();

        return $user;
    }

    private function formatParams(Request $request, ?User $user = null): array
    {
        $formatted = [
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
        ];

        if (! $user) {
            $formatted['password'] = bcrypt($request->password);
            $formatted['status'] = UserStatus::ACTIVATED->value;
        }

        return $formatted;
    }

    public function isAccessible(User $user)
    {
        if ($user->is_default) {
            throw ValidationException::withMessages(['message' => trans('user.errors.permission_denied')]);
        }

        if (! \Auth::user()->is_default && $user->hasRole('admin')) {
            throw ValidationException::withMessages(['message' => trans('user.errors.permission_denied')]);
        }

        if ($user->id == \Auth::id()) {
            throw ValidationException::withMessages(['message' => trans('user.errors.permission_denied')]);
        }
    }

    public function update(Request $request, User $user): void
    {
        \DB::beginTransaction();

        $user->forceFill($this->formatParams($request, $user))->save();

        if ($request->force_change_password) {
            $user->password = bcrypt($request->password);
            $user->save();
        }

        \DB::table('model_has_roles')->whereModelType('User')->whereModelId($user->id)->whereTeamId(session('team_id'))->delete();

        $user->assignRole(SpatieRole::find($request->role_ids));

        \DB::commit();
    }

    public function deletable(User $user): void
    {
        $contactExists = Contact::whereUserId($user->id)->exists();

        if ($contactExists) {
            throw ValidationException::withMessages(['message' => trans('global.associated_with_dependency', ['attribute' => trans('user.user'), 'dependency' => trans('contact.contact')])]);
        }

        $transactionExists = Transaction::whereUserId($user->id)->exists();

        if ($transactionExists) {
            throw ValidationException::withMessages(['message' => trans('global.associated_with_dependency', ['attribute' => trans('user.user'), 'dependency' => trans('finance.transaction.transaction')])]);
        }
    }
}
