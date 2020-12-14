<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Http\Request;
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
            $this->setCookiePin($request->$pin);
            return $request->cookie('pin');
        } catch (\Exception $e) {
            return response_api_server_error($e->getMessage());
        }
    }

    public function validatePin()
    {
    }

    protected function setCookiePin(Request $request)
    {
        Cookie::queue('name', $request->pin, 10);
    }

    protected function getCookiePin(Request $request)
    {
        $value = $request->cookie('pin');
        return $value;
    }

    protected function removeCookiePin(Response $response)
    {
    }
}
