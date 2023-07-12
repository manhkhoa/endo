<?php

namespace App\Actions\Auth;

use App\Helpers\IpHelper;
use App\Helpers\SysHelper;
use App\Http\Resources\AuthUserResource;
use App\Models\User;
use App\Notifications\Auth\SendEmailOtp;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OtpLogin
{
    use ThrottlesLogins;

    public function maxAttempts(): int
    {
        return config('config.auth.login_throttle_max_attempts', 5);
    }

    public function decayMinutes(): int
    {
        return config('config.auth.login_throttle_lock_timeout', 2);
    }

    public function username(): string
    {
        return 'email';
    }

    private function getCacheKey(Request $request, User $user): string
    {
        return 'otp_'.$request->method.'_'.$user->id.'|'.IpHelper::getClientIp();
    }

    /**
     * Request for OTP
     */
    public function request(Request $request): void
    {
        $this->validateMethod($request);

        if ($request->has('otp')) {
            throw ValidationException::withMessages(['message' => trans('general.errors.invalid_action')]);
        }

        $methodName = 'send'.title_case($request->method).'Otp';
        $this->$methodName($request);
    }

    /**
     * Validate OTP
     *
     * @param  Request  $request
     */
    public function confirm(Request $request): array
    {
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = $this->getUser($request, true);

        if ($request->otp != cache($this->getCacheKey($request, $user))) {
            $this->incrementLoginAttempts($request);
            throw ValidationException::withMessages(['otp' => trans('auth.login.errors.failed')]);
        }

        $this->clearLoginAttempts($request);

        cache()->forget($this->getCacheKey($request, $user));

        if (! $request->device_name) {
            \Auth::login($user);
        }

        $user->setCurrentTeamId();

        activity('user')->log('logged_in');

        return [
            'message' => __('auth.login.messages.logged_in'),
            'user' => AuthUserResource::make($user),
            'token' => $request->device_name ? $user->createToken($request->device_name)->plainTextToken : null,
            'two_factor_set' => false,
        ];
    }

    private function validateMethod(Request $request): void
    {
        $method = $request->method;

        if (! in_array($method, ['sms', 'email'])) {
            throw ValidationException::withMessages(['message' => trans('general.invalid_action')]);
        }

        if (! config('config.auth.enable_'.$method.'_otp_login')) {
            throw ValidationException::withMessages(['message' => trans('general.errors.feature_not_available')]);
        }
    }

    private function sendEmailOtp(Request $request): void
    {
        $user = $this->getUser($request);

        $otp = rand(100000, 999999);

        $user->notify(new SendEmailOtp($otp));

        cache([$this->getCacheKey($request, $user) => $otp], config('config.auth.otp_login_lifetime', 10) * 60);
    }

    private function sendSmsOtp(Request $request): void
    {
        $user = $this->getUser($request);

        $otp = rand(100000, 999999);

        // (new User)->forceFill([
        //     'phone' => $user->phone,
        // ])->notify(new SendEmailOtp($otp));

        cache([$this->getCacheKey($request, $user) => $otp], config('config.auth.otp_login_lifetime', 10) * 60);
    }

    private function getUser(Request $request, $setSession = false): User
    {
        $query = User::query();

        if ($request->method === 'email') {
            $query->whereEmail($request->email);
        } elseif ($request->method === 'sms') {
            $query->wherePhone($request->email);
        }

        $user = $query->first();

        if (! $user) {
            throw ValidationException::withMessages(['message' => trans('global.could_not_find', ['attribute' => trans('user.user')])]);
        }

        if ($user->current_team_id && $setSession) {
            session(['team_id' => $user->current_team_id]);
            SysHelper::setTeam($user->current_team_id);
        }

        $user->validateStatus(false);

        return $user;
    }
}
