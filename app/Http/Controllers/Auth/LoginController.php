<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\Login;
use App\Actions\Auth\OtpLogin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\OtpLoginRequest;

class LoginController extends Controller
{
    /**
     * Instantiate a new controller instance
     */
    public function __construct()
    {
        $this->middleware('feature.available:auth.enable_otp_login')->only(['otpRequest', 'otpConfirm']);
    }

    /**
     * Login user
     */
    public function login(LoginRequest $request, Login $login)
    {
        return response()->success($login->execute($request));
    }

    /**
     * Login user with OTP
     */
    public function otpRequest(OtpLoginRequest $request, OtpLogin $otpLogin)
    {
        $otpLogin->request($request);

        return response()->success(['message' => trans('auth.login.otp_sent')]);
    }

    /**
     * Login user with OTP
     */
    public function otpConfirm(OtpLoginRequest $request, OtpLogin $otpLogin)
    {
        return response()->success($otpLogin->confirm($request));
    }

    /**
     * Logout user
     */
    public function logout()
    {
        \Auth::user()->logout();

        return response()->success(['message' => trans('auth.login.logged_out')]);
    }
}
