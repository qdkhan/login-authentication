<?php

namespace App\Http\Controllers\API;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected function create(Request $request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    protected function login(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email|exists:users,email',
                'password' => 'required'
            ]
        );

        if($validator->fails()) return  $validator->errors();

        if(Auth::attempt($request->only('email', 'password')))
        {
            $user = Auth::user();
            $token = $user->createToken('loginToken')->plainTextToken;
            // $token = explode('|', $token);
            // $user->accessToken = $token[1];
            $user->accessToken = ltrim(strstr($token, '|'), '|');
            return $user;
        }

        return 'Invalid Credentials';
        
    }

    protected function getUserList (Request $request)
    {
        $users = User::all();
        return $users;
    }
}
