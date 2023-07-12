<?php

namespace App\Actions\Auth;

use App\Http\Resources\AuthUserResource;
use App\Models\User;
use App\Notifications\Auth\EmailChangeConfirmation;
use App\Notifications\Auth\UsernameChangeConfirmation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AccountUpdate
{
    public function execute(Request $request)
    {
        $user = \Auth::user();

        $pendingUpdate = $user->pending_update;

        if ($user->email != $request->email) {
            $otp = Str::random(6);
            $pendingUpdate['email'] = $request->email;
            $pendingUpdate['new_email_otp'] = $otp;

            (new User)->forceFill([
                'email' => $request->email,
            ])->notify(new EmailChangeConfirmation($otp));
        }

        if ($user->is_default && $user->username != $request->username) {
            throw ValidationException::withMessages(['message' => trans('user.errors.permission_denied')]);
        }

        if ($user->username != $request->username) {
            $otp = Str::random(6);
            $pendingUpdate['username'] = $request->username;
            $pendingUpdate['existing_email_otp'] = $otp;

            $user->notify(new UsernameChangeConfirmation($otp));
        }

        $profileUpdated = true;
        $message = trans('global.updated', ['attribute' => trans('user.profile.profile')]);

        if ($pendingUpdate) {
            $profileUpdated = false;
            $message = trans('user.profile.verify_otp_to_continue');
            $pendingUpdate['valid_till'] = Carbon::now()->addMinutes(30)->toDateTimeString();
            $user->pending_update = $pendingUpdate;
        }

        $user->save();

        $existingEmailVerification = Arr::get($pendingUpdate, 'existing_email_otp') ? true : false;
        $newEmailVerification = Arr::get($pendingUpdate, 'new_email_otp') ? true : false;

        $user = AuthUserResource::make($user);

        return compact('user', 'message', 'profileUpdated', 'existingEmailVerification', 'newEmailVerification');
    }
}
