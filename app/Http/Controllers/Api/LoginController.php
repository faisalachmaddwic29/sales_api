<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {

        try {
            $input = $request->only('email', 'password');

            $validator = Validator::make($input, $this->rules());

            if ($validator->fails())
                return response_api_form_error('error', $validator->errors());

            $user = User::where('email', $request->input('email'))->first();
            if(!$user){
                return response_api_error('Email dan Password tidak terdaftar', 'invalid_credentials');
            }


            if (!Hash::check($request->password, $user->password)) {
                return response_api_error('Password yang Anda masukan salah', 'invalid_credentials');
            }

            if (!$user->status) {
                return response_api_error('Akun Anda belom diaktifkan oleh Admin', 'user_not_active');
            }


            $dataSuccess = [
                'user_id' => $user->user_id,
                'username' => $user->username,
                'email' => $user->email,
                'telephone' => $user->telephone,
                'user_address_id' => $user->user_address_id,
                'user_role_id' => $user->user_role_id,
                'gender' => $user->gender,
                'status' => $user->status,
                'token' => JWTAuth::attempt($input),
                'expired_id' => JWTAuth::factory()->getTTL(),
            ];

            return response_api_success($dataSuccess);
        } catch (\Exception $e) {
            return response_api_server_error($e->getMessage());
        }
    }

    protected function rules()
    {
        $rules = [
            'email' => 'required|email|min:5|only_text',
            'password' => 'required',
        ];

        return $rules;
    }
}
