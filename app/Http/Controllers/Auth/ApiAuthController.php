<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class ApiAuthController extends Controller
{
    //
    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails())
        {
            return $this->customResponse('Validation error',
                                         $validator->errors()->all(),422,false);
        }
        $request['password']=Hash::make($request['password']);

        $user = User::create($request->toArray());
        $token = $user->createToken('Laravel Password Access Client')->accessToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return $this->customResponse('Registered Succesfully',$response);
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|string|email|max:255',
            'password'=>'required|string|min:6|confirmed',
        ]);
        if ($validator->fails())
        {
            return $this->customResponse('Validation error',
                                         $validator->errors()->all(),422,false);
        }
        $user = User::where('email',$request->email)->first();
        if($user){
            if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
                $token = $user->createToken('laravel Password Access Client')->accessToken;
                $response = [
                    'name'=>$user->name,
                    'token' => $token
                ];
                return $this->customResponse('Login Succesfully',$response);
            }
           
        }
        return $this->customResponse('Invalid Credentials',[],422,false);


    }
    public function logout(Request $request){
        $token = $request->user()->token();
        $token->delete();
        return $this->customResponse('You have been successfully logged out',[]);



    }
}
