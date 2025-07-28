<?php

namespace App\Http\Controllers;

use App\Models\WebhookLog;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\StoreWebhookLogsRequest;
use App\Http\Requests\UpdateWebhookLogsRequest;
use App\Services\WebhookService;

class WebhookLogsController extends Controller
{

    public function handle(Request $request){
        $webhook = WebhookService::process($request);
        return $this->responseJSON($webhook,200);

    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWebhookLogsRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WebhookLog $webhookLogs)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WebhookLog $webhookLogs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWebhookLogsRequest $request, WebhookLog $webhookLogs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WebhookLog $webhookLogs)
    {
        //
    }
}
