<?php

namespace App\Http\Controllers\api\v1\auth;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function App\CPU\translate;

class PassportAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'f_name' => 'required',
            'l_name' => 'required',
            'email' => 'required',
            'password' => 'required|min:8',
            'user_type' => 'required|in:1,2',
        ], [
            'f_name.required' => 'The first name field is required.',
            'l_name.required' => 'The last name field is required.',
        ]);

        if ($validator->fails()) {
            return BackEndHelper::sendError(Helpers::error_processor($validator), 403);
        }

        $user_type = $request->user_type;
        $temporary_token = Str::random(40);

        //user
        if ($user_type == 1) {
//            $validator = Validator::make($request->all(), [
//                'email' => 'required|unique:users,email'
//            ]);
        } //seller
        else {
            $validator = Validator::make($request->all(), [
//                'email' => 'required|unique:users,email',
                'location' => 'required',
                'phone' => 'required|unique:users,phone',
            ]);
        }

        if ($validator->fails()) {
            return BackEndHelper::sendError(Helpers::error_processor($validator), 403);
        }

        $user = User::create([
            'name' => $request->f_name . ' ' . $request->l_name,
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'is_active' => 1,
            'location' => $request->location,
            'user_type' => $request->user_type,
            'password' => bcrypt($request->password),
            'temporary_token' => $temporary_token,
        ]);

        $phone_verification = Helpers::get_business_settings('phone_verification');
        $email_verification = Helpers::get_business_settings('email_verification');
        if ($phone_verification && !$user->is_phone_verified) {
            return response()->json(['temporary_token' => $temporary_token], 200);
        }
        if ($email_verification && !$user->is_email_verified) {
            return response()->json(['temporary_token' => $temporary_token], 200);
        }

        $token = $user->createToken('LaravelAuthApp')->accessToken;

        return response()->json([
            'message' => 'Data Got!',
            'data' => [
                'token' => $token,
                'user' => \App\Resources\User::make($user)
            ]]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return BackEndHelper::sendError(Helpers::error_processor($validator), 403);
        }

        $user_id = $request['email'];
        if (filter_var($user_id, FILTER_VALIDATE_EMAIL)) {
            $medium = 'email';
        } else {
            $count = strlen(preg_replace("/[^\d]/", "", $user_id));
            if ($count >= 9 && $count <= 15) {
                $medium = 'phone';
            } else {
                $errors = [];
                array_push($errors, ['code' => 'email', 'message' => 'Invalid email address or phone number']);

                return BackEndHelper::sendError($errors, 403);
            }
        }

        $data = [
            $medium => $user_id,
            'password' => $request->password
        ];

        $user = User::where([$medium => $user_id])->first();

        if (isset($user) && $user->is_active && auth()->attempt($data)) {
            $user->temporary_token = Str::random(40);
            $user->save();

            $phone_verification = Helpers::get_business_settings('phone_verification');
            $email_verification = Helpers::get_business_settings('email_verification');
            if ($phone_verification && !$user->is_phone_verified) {
                return response()->json(['temporary_token' => $user->temporary_token], 200);
            }
            if ($email_verification && !$user->is_email_verified) {
                return response()->json(['temporary_token' => $user->temporary_token], 200);
            }

            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json([
                'message' => 'Data Got!',
                'data' => [
                    'token' => $token,
                    'user' => \App\Resources\User::make($user)
                ]]);
        } else {
            $errors = [];
            array_push($errors, ['code' => 'auth-001', 'message' => translate('Customer_not_found_or_Account_has_been_suspended')]);
            return BackEndHelper::sendError($errors, 401);
        }
    }

    public function logout(Request $request)
    {

        if (auth('api')->user() != null) {
            auth('api')->user()->AauthAcessToken()->delete();
        }

        return BackEndHelper::sendSuccess('Logged out!', '');
    }

}
