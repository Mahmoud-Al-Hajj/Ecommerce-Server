<?php

namespace App\Http\Admin\Controllers;

use App\Models\WebhookLog;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;

use App\Services\WebhookService;

class WebhookLogsController extends Controller{

    public function WebhookLogs($order){
        $webhook = WebhookService::MockPost($order);
        return $this->responseJSON($webhook,200);
    }
}
