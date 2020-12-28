<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), $this->rules());

            if ($validator->fails()) return response_api_form_error('error', $validator->errors());

            DB::beginTransaction();
            $user = new User();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->telephone = $request->telephone;
            $user->user_address_id = $request->user_address_id ?? null;
            $user->user_role_id = $request->user_role_id ?? null;
            $user->status = $request->status ?? FALSE;
            $user->save();

            $dataEmail = (object)[
                'email' => $user->email,
                'username' => $user->username,
                'token'     => $user->token,
                'email_type' => 'register'
            ];

            DB::commit();
            return response_api_success(
                [
                    'fullName' => $user->username,
                    'telephone' => $user->telephone,
                    'email' => $user->email,
                    'messageTitle' => 'Selamat ya!'
                ],
                'Data Anda sudah terkirim, silahkan menunggu aktivasi dari Admin '
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response_api_server_error($e->getMessage());
        }
    }

    protected function rules()
    {
        $rules = [
            'username' => 'required|only_text|max:100|min:3',
            'email' => 'required|email|min:5|only_text|unique:user,email',
            'telephone' => 'required|phone|min:7|max:20|unique:user,telephone',
            'password' => 'required|min:8|max:100',
        ];

        return $rules;
    }
}
