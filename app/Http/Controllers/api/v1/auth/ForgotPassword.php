<?php

namespace App\Http\Controllers\api\v1\auth;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\CPU\SMS_module;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPassword extends Controller
{
    public function reset_password_request(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return BackEndHelper::sendError(Helpers::error_processor($validator), 403);
        }

        $verification_by = Helpers::get_business_settings('forgot_password_verification');
        DB::table('password_resets')->where('identity', 'like', "%{$request['identity']}%")->delete();

        if ($verification_by == 'email') {
            $customer = User::Where(['email' => $request['identity']])->first();
            if (isset($customer)) {
                $token = Str::random(6);
                DB::table('password_resets')->insert([
                    'identity' => $customer['email'],
                    'token' => $token,
                    'created_at' => now(),
                ]);
                $reset_url = url('/') . '/customer/auth/reset-password?token=' . $token;
                Mail::to($customer['email'])->send(new \App\Mail\PasswordResetMail($token));
                return BackEndHelper::sendSuccess('Email sent successfully.', '');
            }
        } elseif ($verification_by == 'phone') {
            $customer = User::where('phone', 'like', "%{$request['identity']}%")->first();
            if (isset($customer)) {
                $token = rand(1000, 9999);
                DB::table('password_resets')->insert([
                    'identity' => $customer['phone'],
                    'token' => $token,
                    'created_at' => now(),
                ]);
                SMS_module::send($customer->phone, $token);
                return BackEndHelper::sendSuccess('otp sent successfully.', '');
            }
        }
        return BackEndHelper::sendError([['code' => 'not-found', 'message' => 'user not found!']], 404);
    }

    public function otp_verification_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required',
            'otp' => 'required'
        ]);

        if ($validator->fails()) {
            return BackEndHelper::sendError(Helpers::error_processor($validator), 403);
        }

        $id = session('forgot_password_identity');
        $data = DB::table('password_resets')->where(['token' => $request['otp']])
            ->where('identity', 'like', "%{$id}%")
            ->first();

        if (isset($data)) {
            return response()->json(['message' => 'otp verified.'], 200);
        }

        return response()->json(['errors' => [
            ['code' => 'not-found', 'message' => 'invalid OTP']
        ]], 404);
    }

    public function reset_password_submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required',
            'otp' => 'required',
            'password' => 'required|same:confirm_password|min:8',
        ]);

        if ($validator->fails()) {
            return BackEndHelper::sendError(Helpers::error_processor($validator), 403);
        }

        $data = DB::table('password_resets')
            ->where('identity', 'like', "%{$request['identity']}%")
            ->where(['token' => $request['otp']])->first();

        if (isset($data)) {
            DB::table('users')->where('email', 'like', "%{$data->identity}%")
                ->update([
                    'password' => bcrypt(str_replace(' ', '', $request['password']))
                ]);

            DB::table('password_resets')
                ->where('identity', 'like', "%{$request['identity']}%")
                ->where(['token' => $request['otp']])->delete();

            return BackEndHelper::sendSuccess('Password changed successfully.', '');
        }
        return BackEndHelper::sendError([
            ['code' => 'invalid', 'message' => 'Invalid token.']
        ], 404);
    }
}
