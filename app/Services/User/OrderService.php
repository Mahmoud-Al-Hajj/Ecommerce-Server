<?php

namespace App\Services\User;

use App\Models\Order;

class OrderService{

    public static function getAllOrders(){
        return Order::All();
    }
    public static function getOrderById($id){
        return Order::with('orderItems')->findOrFail($id);
        //this will return the order with its items
        //orderItems is the relationship defined in the Order model
    }

    public static function getOrdersByUserId($userId){
        return Order::where('user_id', $userId)->with('orderItems')->get();
    }

    public static function createOrder($data){
        $order = new Order;
        $order->user_id = $data['user_id'] ?? null;
        $order->status = $data['status'] ?? 'Pending';
        $order->total_price = $data['total_price'] ?? 0;
        $order->save();
        return $order;
    }

    public static function updateOrder($id, $data){
        $order = Order::findOrFail($id);
        $order->status = $data['status'] ?? $order->status;
        $order->total_price = $data['total_price'] ?? $order->total_price;
        $order->save();
        return $order;
    }

    public static function deleteOrder($id){
        Order::findOrFail($id)->delete();
        return ["status" => "success"];
    }

}
