<?php

namespace App\Http\Controllers\Auth;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Mail\OtpMail;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\OtpService;
use App\Services\User\ProfileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService, protected OtpService $otpService, protected ProfileService $profileService) {}

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());
        $otp = $this->otpService->generateOtpCode($result['user']);
        return ApiResponse::success([
            'message' => "otp code sent to your email",
            'user_id' => $result['user']->id,
        ], 201);
    }


    public function verifyOtp(VerifyOtpRequest $request)
    {
        $user = User::findOrFail($request->user_id);

        if (!$this->otpService->verifyOtpCode($user, $request->otp)) {
            return ApiResponse::error('Invalid or expired OTP', 422);
        }

        // تفعيل الحساب
        $this->authService->verifyEmail($user);

        // إنشاء Token بعد التفعيل
        $token = $user->createToken('auth_token')->plainTextToken;

        return ApiResponse::success([
            'message' => 'OTP verified successfully',
            'user' => new UserResource($user),
            'token' => $token,
        ], 200);
    }


    public function login(Request $request)
    {
        $result = $this->authService->login($request->only('email', 'password'));
        return ApiResponse::success([
            'user' => new UserResource($result['user']),
            'token' => $result['token'],
            'message' => 'Logged in successfully',
        ], 200);
    }


    public function logout(Request $request)
    {
        // حذف التوكن الحالي للمستخدم
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::success([
            'message' => 'Logged out successfully'
        ], 200);
    }


    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = $this->authService->getUserByEmail($request->email);

        $otp = $this->otpService->generateOtpCode($user);

        Mail::to($user->email)->send(new OtpMail($otp));

        return ApiResponse::success([
            'message' => 'OTP sent to your email',
            'user_id' => $user->id,
        ]);
    }



    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = $this->authService->getUserByEmail(
            email: \App\Models\User::find($request->user_id)->email
        );

        if (!$this->otpService->verifyOtpCode($user, $request->otp)) {
            return ApiResponse::error('Invalid or expired OTP', 422);
        }

        $this->authService->resetPassword($user, $request->password);

        return ApiResponse::success([
            'message' => 'Password reset successfully',
        ]);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $this->authService->changePassword(
            $request->user(),
            $request->current_password,
            $request->new_password
        );

        return ApiResponse::success([
            'message' => 'Password changed successfully',
        ]);
    }

    public function show()
    {
        return ApiResponse::success(
            new UserResource(Auth::user())
        );
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = $this->profileService->update(
            Auth::user(),
            $request->validated()
        );

        return ApiResponse::success(
            new UserResource($user)
        );
    }


}
