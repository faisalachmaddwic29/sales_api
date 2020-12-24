<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgetPassword;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ForgetPasswordController extends Controller
{
    public function forget(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|min:5|exists:App\Models\User,email',
            ]);

            if ($validator->fails())
                return response_api_form_error('error', $validator->errors());


            $user = User::where('email', $request->email)->first();
            $email = $user->email;
            $time = 60;
            $pin =  mt_rand(1000, 9999);

            $dataEmail = (object)[
                'email' => $email,
                'username' => $user->username,
                'pin' => $pin,
                'email-type' => 'Forget Password',
            ];

            DB::beginTransaction();
            DB::table('user')->where('email', $email)->update(['pin' => bcrypt($pin)]);
            DB::commit();

            Mail::to($email)->send(new ForgetPassword($dataEmail));
            return response_api_success([
                'email' => $email,
            ])->withCookie('pin', $pin, $time);
        } catch (\Exception $e) {
            return response_api_server_error($e->getMessage());
        }
    }

    public function validatePin(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|min:5|exists:App\Models\User,email',
                'pin' => 'required|phone|max:4'
            ]);

            if ($validator->fails())
                return response_api_form_error('error', $validator->errors());
            
            $user = User::where('email', $request->email)->first();
            if (Hash::check($request->pin, $user->pin)) {
                return response_api_success(['data' => 'Selamat Anda Berhasil memasukan Pin']);
            } else {
                return response_api_form_error('Maaf Pin yang Anda masukan salah', []);
            }
        } catch (\Exception $e) {
            return response_api_server_error($e->getMessage());
        }
    }

    public function forgetPassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|min:5|exists:App\Models\User,email',
                'password' => 'required|min:8|max:100',
                'pin' => 'required|phone|max:4'
            ]);

            if ($validator->fails())
                return response_api_form_error('error', $validator->errors());

            $user = User::where('email', $request->email)->first();

            $email = $user->email;
            $password = bcrypt($request->password);
            // return $email;

            DB::beginTransaction();
            DB::table('user')->where('email', $email)->update(['password' => $password , 'pin' => null]);
            DB::commit();
            return response_api_success([], 'Password anda telah berhasil dirubah, mulai sekarang masukan password baru anda untuk login kedalam aplikasi.')->withoutCookie('pin');
        } catch (\Exception $e) {
            DB::rollBack();
            return response_api_server_error($e->getMessage());
        }
    }
}
