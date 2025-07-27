<?php

namespace App\Services\Common;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
class AuthService
{
  static public function login(LoginRequest $request){

        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {return null;}

        $user = Auth::user();
        $user->token = $token;
        return $user;


    }
    static function register(RegisterRequest $request){

        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $token = Auth::login($user);

        $user->token = $token;
        return response()->json($user, 201);
    }
}
