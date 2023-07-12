<?php

namespace App\Services\Employee;

use App\Enums\UserStatus;
use App\Http\Resources\UserSummaryResource;
use App\Models\Employee\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role as SpatieRole;

class UserService
{
    private function ensureEmailDoesntBelongToOtherEmployee(Request $request, Employee $employee)
    {
        $emailBelongsToOtherEmployee = Employee::query()
            ->where('id', '!=', $employee->id)
            ->whereHas('contact', function ($q) use ($request) {
                $q->where('email', $request->email);
            })->exists();

        if ($emailBelongsToOtherEmployee) {
            throw ValidationException::withMessages(['message' => trans('contact.login.email_belongs_to_other_contact')]);
        }
    }

    private function ensureEmailDoesntBelongToUser(Request $request)
    {
        $emailBelongsToUser = User::whereEmail($request->email)->exists();

        if ($emailBelongsToUser) {
            throw ValidationException::withMessages(['message' => trans('contact.login.email_belongs_to_team_member')]);
        }
    }

    public function confirm(Request $request, Employee $employee): bool
    {
        $request->validate([
            'email' => 'required|email',
        ], [], [
            'email' => trans('contact.login.props.email'),
        ]);

        $this->ensureEmailDoesntBelongToOtherEmployee($request, $employee);

        $this->ensureEmailDoesntBelongToUser($request);

        return User::whereEmail($request->email)->exists();
    }

    public function fetch(Employee $employee): array|UserSummaryResource
    {
        $employee->load('contact.user.roles');

        if (! $employee->user_id) {
            return [];
        }

        return UserSummaryResource::make($employee->contact->user);
    }

    public function create(Request $request, Employee $employee)
    {
        $this->ensureEmailDoesntBelongToOtherEmployee($request, $employee);

        $this->ensureEmailDoesntBelongToUser($request);

        $employee->load('contact.user');

        $user = $employee->contact?->user;

        if ($user) {
            throw ValidationException::withMessages(['message' => trans('general.errors.invalid_action')]);
        }

        \DB::beginTransaction();

        $user = User::forceCreate([
            'name' => $employee->contact->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'status' => UserStatus::ACTIVATED->value,
        ]);

        $user->assignRole(SpatieRole::find($request->role_ids));

        $contact = $employee->contact;
        $contact->user_id = $user->id;
        $contact->save();

        \DB::commit();
    }

    public function update(Request $request, Employee $employee)
    {
        $this->ensureEmailDoesntBelongToOtherEmployee($request, $employee);

        $employee->load('contact.user');

        $user = $employee->contact?->user;

        if (! $user) {
            throw ValidationException::withMessages(['message' => trans('global.could_not_find', ['attribute' => trans('user.user')])]);
        }

        \DB::beginTransaction();

        \DB::table('model_has_roles')->whereModelType('User')->whereModelId($user->id)->whereTeamId(session('team_id'))->delete();

        $user->assignRole(SpatieRole::find($request->role_ids));

        \DB::commit();
    }
}
