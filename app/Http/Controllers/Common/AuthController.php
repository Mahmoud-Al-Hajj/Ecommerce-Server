<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

use  App\Services\Common\AuthService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller{

    public function login(LoginRequest $request){
        $user = AuthService::login($request);
        return $user ? $this->responseJSON($user,200) : $this->responseJSON(null, "error", 401);
    }

    public function register(RegisterRequest $request){
        $user = AuthService::register($request);
        return $this->responseJSON($user,200);
    }


}
