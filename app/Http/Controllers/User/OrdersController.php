<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\User\OrderService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\StoreOrdersRequest;
use App\Http\Requests\UpdateOrdersRequest;
use App\Http\Requests\UpdateOrderStatusRequest;

class OrdersController extends Controller
{

    public function createOrder(CreateOrderRequest $request){
        $order = OrderService::createOrder($request);
        return $this->responseJSON($order, 'Order placed', 201);
    }

    public function myOrders(){
        $orders = OrderService::getOrdersByUserId();
        return $this->responseJSON('My orders', $orders, 201);
    }
    public function getOrderById($id){
        $tracking = OrderService::getOrderById($id);
        return $this->responseJSON('Order details:', $tracking, 200);
    }
    public function allOrders(Request $request){
        $orders = OrderService::getAllOrders($request);
        return $this->responseJSON('All orders returned', $orders, 201);
    }

    public function updateOrderStatus(UpdateOrderStatusRequest $request, $id){
        $order = OrderService::updateOrderStatus($request, $id);
        return $this->responseJSON('Order status updated', $order, 200);
    }

    public function deleteOrder($id){
        $order = OrderService::deleteOrder($id);
        return $this->responseJSON('Order deleted', $order, 200);
    }

    public function getTodaysRevenue(){
        $revenue = OrderService::getTodayRevenue();
        return $this->responseJSON($revenue,'Todays revenue', 200);
    }
}
