<?php

namespace App\Services\User;

use Exception;
use App\Events\OrderPlaced;
use App\Events\OrderStatusShipped;
use App\Models\ProductSize;
use App\Models\AuditLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderPerHour;
use App\Models\Product;
use App\Services\WebhookService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class OrderService{

    public static function getAllOrders($request){
        $perPage = $request->per_page;
        if (!$perPage) $perPage = 1000;
        return Order::with('items.product', 'user')->paginate($perPage);
    }
    public static function getOrderById($id){
        return Order::with('user', 'items.product')->findOrFail($id);
    }

    public static function getOrdersByUserId(){
        $userId = Auth::id();
        return Order::where('user_id', $userId)->with('items.product')->get();
    }

    public static function createOrder($request){
        $order = new Order;
        $userId = Auth::id();
        $order->user_id = $userId;
        $order->status =  $request->status ?? 'Pending';
        $order->total_price = 0;
        $order->save();

        $total = 0;
        if (!empty($request['items']) && is_array($request['items'])) {
            foreach ($request['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                $productSize = ProductSize::where('product_id', $item['product_id'])
                    ->where('size', $item['item_size'])
                    ->first();

                if (!$productSize) {
                    throw new Exception("Size '{$item['item_size']}' not available for product ID: {$product->id}");
                }

                if ($productSize->stock < $item['quantity']) {
                    throw new Exception("Insufficient stock for product ID: {$product->id}, size: {$item['item_size']}. Available: {$productSize->stock}, Requested: {$item['quantity']}");
                }

                $orderItem = new OrderItem;
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $product->price;
                $orderItem->item_size = $item['item_size'] ?? null;
                $orderItem->save();

                $productSize->stock -= $item['quantity'];
                $productSize->save();
                $total += $product->price * $item['quantity'];
            }
            $order->total_price = $total;
            $order->save();
            WebhookService::MockPost($order->load('items', 'user'));
        } else {
            throw new Exception("No items provided for the order.");
            return;
        }

        event(new OrderPlaced($order));
        return [
            'order_id' => $order->id,
            $order->load(['items.product']),
            'message' => 'Order placed successfully'
        ];
    }


    public static function updateOrderStatus($request, $id){
        $order = Order::findOrFail($id);
        $newStatus = $request->status;
        $oldStatus = $order->status;
        $order->status = $newStatus;
        $order->save();

        $audit = new AuditLog;
        $audit->user_id = Auth::id();
        $audit->order_id = $order->id;
        $audit->from_status = $oldStatus;
        $audit->to_status = $newStatus;
        $audit->save();

        if ($newStatus === 'Shipped') {
            event(new OrderStatusShipped($order));
        }
        return $order;
    }


    public static function getTodayRevenue(){
        $thirtyDaysAgo = now()->subDays(30)->startOfDay();
        $today = now()->endOfDay();

        $orders = Order::whereBetween('created_at', [$thirtyDaysAgo, $today])->get();
        $totalRevenue = $orders->sum('total_price');
        return $totalRevenue;
    }

    public static function deleteOrder($id){
        $order = Order::findOrFail($id);
        $orderItems = $order->items;
        $order->items()->delete();
        $order->delete();
        foreach ($orderItems as $item) {
            $product = Product::findOrFail($item->product_id);
            $product->quantity += $item->quantity;
            $product->save();
        }
        return ["status" => "success, deleted order"];
    }
}
