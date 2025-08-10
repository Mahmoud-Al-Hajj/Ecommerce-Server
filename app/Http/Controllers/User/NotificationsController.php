<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller{

    public function getAllNotifications(Request $request){
        $user = Auth::user();
        $notifications = $user->notifications;
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'notifications' => $notifications
        ]);
    }
    public function markAsRead(Request $request, $id){
        $user = Auth::user();
        $notification = $user->notifications->find($id);
        if ($notification) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marked as read']);
        }

        return response()->json(['message' => 'Notification not found'], 404);
    }

    public function markAllAsRead(Request $request){
        $user = Auth::user();
    $user->unreadNotifications->markAsRead();
        //$user->unreadNotifications->update(['read_at' => now()]);

        return response()->json(['message' => 'All notifications marked as read']);
    }
}

//source: documentation of laravel
//source: https://laravel.com/docs/10.x/notifications#retrieving-notifications
