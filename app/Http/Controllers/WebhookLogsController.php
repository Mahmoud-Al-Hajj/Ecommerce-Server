<?php

namespace App\Http\Controllers;

use App\Models\WebhookLog;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StoreWebhookLogsRequest;
use App\Http\Requests\UpdateWebhookLogsRequest;
use App\Services\WebhookService;

class WebhookLogsController extends Controller{

    public function WebhookLogs(Request $request){
        $webhook = WebhookService::process($request);
        return $this->responseJSON($webhook,200);

    }

}
