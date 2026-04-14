<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\SendOtpMail;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /* ================= REGISTER ================= */

    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255',
        'email' => 'required|email',
        'password' => 'required|min:6',
        'phone' => 'nullable|string|max:15',
        'dob' => 'required|date',
        'pronouns' => 'required|string|max:50',
    ]);

    $otp = rand(100000, 999999);

    $user = User::where('email', $request->email)->first();

    if ($user) {

        // If already verified → stop
        if ($user->is_verified) {
            return response()->json([
                'status' => false,
                'message' => 'Email already registered. Please login.'
            ], 400);
        }

        // If not verified → update user + resend OTP
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'dob' => $request->dob,
            'pronouns' => $request->pronouns,
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

    } else {

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'dob' => $request->dob,
            'pronouns' => $request->pronouns,
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
            'is_verified' => false,
        ]);
    }

    Mail::to($user->email)->send(
        new SendOtpMail($otp, $user->name)
    );

    return response()->json([
        'status' => true,
        'message' => 'OTP sent to your email.'
    ]);
}
    /* ================= VERIFY OTP ================= */

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $user = User::where('email', $request->email)
                    ->where('otp', $request->otp)
                    ->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP'
            ], 400);
        }

        if ($user->otp_expires_at < now()) {
            return response()->json([
                'status' => false,
                'message' => 'OTP expired'
            ], 400);
        }

        $user->update([
            'is_verified' => true,
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Email verified successfully'
        ]);
    }

    /* ================= RESEND OTP ================= */

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        $otp = rand(100000, 999999);

        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        Mail::raw("Your new OTP is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Resend OTP');
        });

        return response()->json([
            'status' => true,
            'message' => 'OTP resent successfully'
        ]);
    }

    /* ================= LOGIN ================= */

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();

        // if (!$user->is_verified) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Please verify your email first'
        //     ], 403);
        // }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => $user
        ]);
    }

    public function forgotPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email'
    ]);

    $user = User::where('email', $request->email)->first();

    $otp = rand(100000, 999999);

    $user->update([
        'reset_otp' => $otp,
        'reset_otp_expires_at' => now()->addMinutes(10),
    ]);

    Mail::raw("Your password reset OTP is: $otp", function ($message) use ($user) {
        $message->to($user->email)
                ->subject('Password Reset OTP');
    });

    return response()->json([
        'status' => true,
        'message' => 'Reset OTP sent to your email.'
    ]);
}

public function verifyResetOtp(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'otp' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || $user->reset_otp != $request->otp) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid OTP'
        ], 400);
    }

    if ($user->reset_otp_expires_at < now()) {
        return response()->json([
            'status' => false,
            'message' => 'OTP expired'
        ], 400);
    }

    return response()->json([
        'status' => true,
        'message' => 'OTP verified successfully'
    ]);
}

public function resetPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'otp' => 'required',
        'password' => 'required|min:6|confirmed'
    ]);

    $user = User::where('email', $request->email)
                ->where('reset_otp', $request->otp)
                ->first();

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Invalid OTP'
        ], 400);
    }

    if ($user->reset_otp_expires_at < now()) {
        return response()->json([
            'status' => false,
            'message' => 'OTP expired'
        ], 400);
    }

    $user->update([
        'password' => Hash::make($request->password),
        'reset_otp' => null,
        'reset_otp_expires_at' => null,
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Password reset successfully'
    ]);
}

    /* ================= GOOGLE LOGIN ================= */

    public function googleLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'google_id' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'username' => Str::slug($request->name) . rand(100,999),
                'email' => $request->email,
                'google_id' => $request->google_id,
                'password' => Hash::make(Str::random(16)),
                'is_verified' => true,
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'token' => $token,
            'user' => $user
        ]);
    }

    /* ================= ME (current user) ================= */

    public function me(Request $request)
    {
        return response()->json([
            'status' => true,
            'user' => $request->user(),
        ]);
    }

    /* ================= LOGOUT ================= */

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}