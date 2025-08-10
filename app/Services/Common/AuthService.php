<?php

namespace App\Services\Common;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;



class AuthService{

    static public function login($request){

        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);

        if (!$token) {return null;}

        $user = Auth::user();
        $token = JWTAuth::fromUser($user);
        $user->token = $token;
        return $user;

    }

    static function register($request){
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $user->save();

        // Generate JWT token
        $token = JWTAuth::fromUser($user);
        $user->token = $token;
        return $user;
    }

    static function logout(){
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    static function getUsersCount(){
        return User::count();
    }
}
