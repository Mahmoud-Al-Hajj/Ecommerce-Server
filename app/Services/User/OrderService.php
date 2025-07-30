<?php

namespace App\Services\User;

use App\Models\AuditLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPerHour;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderService{

    public static function getAllOrders(){
        return Order::with('items.product')->get();
    }
    public static function getOrderById($id){
        return Order::with('user', 'items.product')->findOrFail($id);
    }

    public static function getOrdersByUserId($userId){
        return Order::where('user_id', $userId)->with('items.product')->get();
    }

    public static function createOrder($request){
        $order = new Order;
        $order->user_id = Auth::id() ?? null;
        $order->status = 'Pending';
        $order->total_price = 0;

        $total = 0;
        if (!empty($request['items']) && is_array($request['items'])) {
            foreach ($request['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $orderItem = new OrderItem;
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $product->price;
                $orderItem->item_size = $item['item_size'] ?? null;
                $orderItem->save();
                $total += $product->price * $item['quantity'];
            }
        }
        $order->total_price = $total;
        $order->save();


        self::logOrderPerHour($order);
        return $order->load(['items.product']);
    }


    public static function updateOrderStatus($id, $newStatus){
//user here is admin
    $order = Order::findOrFail($id);
    $oldStatus = $order->status;
    $order->status = $newStatus;
    $order->save();

    $audit = new AuditLog;
    $audit->user_id = Auth::id();
    $audit->action = 'status_update_order';
    $audit->order_id = $order->id;
    $audit->old_status = $oldStatus;
    $audit->new_status = $newStatus;
    $audit->save();
    }


    public static function logOrderPerHour(Order $order){
        $now = now();
        $existing= OrderPerHour::where('date', $now->toDateString())->where('time', $now->format('H'))->first();

        if ($existing) {
            $existing->order_count += 1;
            $existing->revenue += $order->total_price;
        $existing->save();
        return $existing;
    } else{
        $record = new OrderPerHour;
        $record->date = $now->toDateString();
        $record->time = $now->format('H');
        $record->order_count = 1;
        $record->revenue = $order->total_price;
        $record->save();
        return $record;
    }
}


    public static function deleteOrder($id){
        $order= Order::findOrFail($id);
        $order->items()->delete();
        $order->delete();
        return ["status" => "success, deleted order"];
    }
}
