<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

class UserController extends Controller
{
    public function register(Request $request){

        $rules = [
            "name" => "required|min:3|max:50",
            "email" => "required|email|unique:users",
            "password" => "required|min:5|max:20",
            "address" => "required",
            "phone" => "required",
            "nif" => "required",
        ];

        $authRules = [
            "email" => "required|email|unique:users",
            "password" => "required|min:5|max:20",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){
            return $this->error($validator->errors());
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $customer = new Customer();
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->phone = $request->phone;
        $customer->nif = $request->nif;
        $customer->user_id = $user->id;
        $customer->save();

        Auth::attempt($authRules);

        return $this->getToken($this->getPersonalAccessToken(), "Registration Success");
    }

    public function login(Request $request){

        $rules = [
            "email" => "required|email",
            "password" => "required",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->error($validator->errors());
        }
        if (Auth::attempt($request->validate($rules) , true)) {
            return $this->getToken($this->getPersonalAccessToken(), "Login Success");
        }

        return $this->error("Oops");
    }


    /**
     * Auxiliary functions
    */


    protected function getToken($personalAccessToken, $message = null, $code = 200)
	{
		$tokenData = [
			'access_token' => $personalAccessToken->accessToken,
            'token_type' => 'Bearer',
            'expires_at' =>$personalAccessToken->token->expires_at,
		];

		return $this->success($tokenData, $message, $code);
	}

    protected function success($data, $message = null, $code = 200)
	{
		return response()->json([
			'status'=> 'Success',
			'message' => $message,
			'data' => $data
		], $code);
	}

	protected function error($message = null, $code = 400)
	{
		return response()->json([
			'status'=>'Error',
			'message' => $message,
			'data' => null
		], $code);
	}

    public function getPersonalAccessToken()
    {
        return Auth::user()->createToken('Personal Access Token');
    }


}
