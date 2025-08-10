<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\User\ChatService;

class ChatController extends Controller{
    public function ask(Request $request){
        $response = ChatService::ask($request);
        return $this->ResponseJSON($response, 200);
    }

}
