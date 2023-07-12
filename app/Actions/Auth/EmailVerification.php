<?php

namespace App\Actions\Auth;

use App\Enums\UserStatus;
use App\Models\User;
use App\Notifications\Auth\UserRegistered;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EmailVerification
{
    public function execute(Request $request): void
    {
        $user = $this->getUser($request);

        $user->email_verified_at = now();
        $user->status = config('config.auth.enable_account_approval') ? UserStatus::PENDING_APPROVAL->value : UserStatus::ACTIVATED->value;
        $user->save();

        $user->notify(new UserRegistered($user));
    }

    /**
     * Get user from verification token
     */
    private function getUser(Request $request): User
    {
        $user = User::where('meta->activation_token', $request->token)->first();

        if (! $user) {
            throw ValidationException::withMessages(['message' => __('auth.register.errors.invalid_verification_token')]);
        }

        if ($user->status != UserStatus::PENDING_VERIFICATION->value) {
            throw ValidationException::withMessages(['message' => __('general.errors.invalid_action')]);
        }

        return $user;
    }
}
