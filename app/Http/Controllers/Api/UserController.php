<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CancelOrderRequest;
use App\Http\Requests\Api\SendMessageRequest;
use App\Models\CancelOrder;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Order;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\Venmo;
use App\Models\Zelle;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function home($user_id)
    {
        $pending = Order::where('user_id', $user_id)->where('status', 0)->count();
        $pending_order = Order::where('user_id', $user_id)->where('status', 0)->get();
        $accept = Order::where('user_id', $user_id)->where('status', 1)->count();
        $start = Order::where('user_id', $user_id)->where('status', 2)->count();
        $delivered = Order::where('user_id', $user_id)->where('status', 3)->count();
        $complete = Order::where('user_id', $user_id)->where('status', 4)->count();
        $canceled = Order::where('user_id', $user_id)->where('status', 5)->count();

        $zelle = Zelle::latest()->first();
        $venmo = Venmo::latest()->first();

        return response()->json([
            'status' => true,
            'action' => "Home",
            'data' => array(
                'pending' => $pending,
                'accept' => $accept,
                'start' => $start,
                'delivered' => $delivered,
                'complete' => $complete,
                'canceled' => $canceled,
                'pending_order' => $pending_order,
                'venmo' => $venmo,
                'zelle' => $zelle
            )
        ]);
    }

    public function counter($uuid)
    {

        $counter = User::where('uuid', $uuid)->first();

        if ($counter) {
            $message = Message::where('user_id', $counter->uuid)->where('send_by', 'admin')->where('is_read', 0)->count();
            $notify = Notification::where('user_id', $uuid)->wehre('is_read', 0)->count();
            return response()->json([
                'status' => true,
                'action' => "Counter",
                'data' => array(
                    'message_unread_counter' => $message,
                    'notification_unread_counter' => $notify,
                )
            ]);
        }

        return response()->json([
            'status' => false,
            'action' => "User not found",
        ]);
    }
    public function notification($user_id)
    {
        $user = User::where('uuid', $user_id)->first();
        if ($user) {
            $notifications = Notification::with('user')->where('user_id', $user_id)->latest()->paginate(12);
            return response()->json([
                'status' => true,
                'action' => "Notification",
                'data' => $notifications
            ]);
        }
        return response()->json([
            'status' => false,
            'action' => "User not found",
        ]);
    }
}
