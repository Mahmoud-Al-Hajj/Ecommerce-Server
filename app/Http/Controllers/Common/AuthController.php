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

    public function register(RegisterRequest $request) {
        $response = AuthService::register($request);
        if (!$response) {
            return $this->responseJSON(null, "Registration failed", 400);
        }
        return $this->responseJSON($response, 201);
    }
    public function logout() {
        $logout= AuthService::logout();
        return $this->responseJSON($logout,200);

    }

    public function getUsersCount() {
        $count = AuthService::getUsersCount();
        return $this->responseJSON($count, 200);
    }

}
