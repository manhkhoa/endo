<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'throttle' => 'Quá nhiều lần đăng nhập lỗi. Vui lòng thử lại sau :seconds giây. ログイン試行が多すぎます。 :seconds 秒後にもう一度お試しください。',
    'register' => [
        'register' => 'Register',
        'register_title' => 'Create a new account',
        'email_request' => 'Request verification email',
        'email_verification' => 'Email verification',
        'email_request_description' => 'If you didn\'t get email for verification, please request for verification email here.',
        'email_verified' => 'Your email is verified, you can login.',
        'errors' => [
            'invalid_verification_token' => 'Invalid verification token.',
        ],
        'registered' => 'Registration successfull.',
        'registered' => 'Registration successfull.',
        'registered_status_activated' => 'Registration successfull, you can now login.',
        'registered_status_pending_verification' => 'Registration successfull, please check your email for verification.',
        'pending_verification_email_sent' => 'Please check your email for verification.',
        'registered_status_pending_approval' => 'Registration successfull, your account is pending for approval.',
        'props' => [
            'name' => 'Name',
            'email' => 'Email',
            'username' => 'Tên đăng nhập ユーザー名',
            'password' => 'Mật khẩu パスワード',
            'password_confirmation' => 'Confirm Password',
        ],
    ],
    'login' => [
        'login' => 'Đăng nhập ログイン',
        'login_title' => 'Đăng nhập ログイン',
        'props' => [
            'email' => 'Email メール',
            'email_or_username' => 'Email hoặc tên đăng nhập メールまたはユーザ名',
            'phone' => 'Điện thoại 電話番号',
            'method' => 'Phương thức OTP. OTP方式',
            'username' => 'Tên đăng nhập ユーザー名',
            'password' => 'Mật khẩu パスワード',
            'otp' => 'Mật khẩu tạm thời ワンタイムパスワード',
            'otp_short' => 'OTP',
            'remember_me' => 'Nhớ thông tin 情報保存',
        ],
        'errors' => [
            'unauthenticated' => 'Your session is expired, please login again.',
            'failed' => 'These credentials do not match our records.',
            'permission_disabled' => 'You are not allowed to login.',
            'invalid_status' => [
                'banned' => 'Your account is banned.',
                'disapproved' => 'Your account request is disapproved.',
                'not_activated' => 'Your account is not yet activated.',
                'pending_approval' => 'Your account is not yet approved.',
                'pending_verification' => 'Your account is not yet verified.',
            ],
        ],
        'logout' => 'Logout',
        'otp_sent' => 'Login OTP sent.',
        'logged_in' => 'You are successfully logged in.',
        'logged_out' => 'You are successfully logged out.',
    ],
    'security' => [
        'props' => [
            'code' => 'Code',
        ],
        'errors' => [
            'two_factor_security_pending' => 'Two factor security pending.',
            'invalid_code' => 'Invalid two factor code.',
        ],
    ],
    'screen_lock' => [
        'props' => [
            'password' => 'Password',
        ],
        'errors' => [
            'screen_lock_pending' => 'Please enter password to continue.',
            'password_mismatch' => 'Password mismatch.',
        ],
    ],
    'password' => [
        'password_title' => 'Bạn quên mật khẩu? パスワードをお忘れですか。',
        'change_password' => 'Thay đổi mật khẩu パスワードを変更する',
        'request_password' => 'Yêu cầu mật khẩu mới 新しいパスワードを要求する',
        'verify_token' => 'Xác thực トークンの検証',
        'reset_password' => 'Thiết lập lại mật khẩu',
        'props' => [
            'email' => 'Email メール',
            'code' => 'Code',
            'current_password' => 'Mật khẩu hiện tại 現在のパスワード',
            'new_password' => 'Mật khẩu mới 新しいパスワード',
            'new_password_confirmation' => 'Xác nhận mật khẩu mới 新しいパスワードを確認',
        ],
        'errors' => [
            'password_mismatch' => 'Current password do not match.',
            'account_not_activated' => 'User account is not activated.',
            'code_mismatch' => 'This password reset code is invalid.',
            'code_expired' => 'This password reset code is expired.',
            'different' => 'The password should be different than previous password.',
        ],
        'forgot_password' => 'Quên mật khẩu パスワード再設定',
        'reset' => 'Your password has been reset.',
        'user' => 'We can\'t find a user with that e-mail address.',
        'sent' => 'We have e-mailed your password reset link.',
        'changed' => 'Mật khẩu được thay đổi thành công パスワードの変更が成功しました。',
    ],
];
