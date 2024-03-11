<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CancelOrderRequest;
use App\Http\Requests\Api\SendMessageRequest;
use App\Models\CancelOrder;
use App\Models\Message;
use App\Models\Order;
use App\Models\UserAnswer;
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
            )
        ]);
    }
   
}
