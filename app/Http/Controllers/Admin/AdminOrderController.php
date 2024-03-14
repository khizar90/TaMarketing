<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CancelOrder;
use App\Models\Message;
use App\Models\Order;
use App\Models\User;
use App\Models\UserAnswer;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function list($status)
    {
        $pending = Order::where('status', 0)->count();
        $accept = Order::where('status', 1)->count();
        $start = Order::where('status', 2)->count();
        $delivered = Order::where('status', 3)->count();

        $orders = Order::with(['user:uuid,name,image,email,verify'])->where('status', $status)->get();
        return view('order.index', compact('orders','pending','accept','start','delivered'));
    }
    public function changeStatus(Request $request, $order_id, $status)
    {
        $order = Order::find($order_id);
        if ($order) {
            if ($status == 5) {
                $cancel = new CancelOrder();
                $cancel->order_id = $order_id;
                $cancel->reason = $request->reason;
                $cancel->cancel_by = 'admin';
                $cancel->save();
                $order->status = $status;
                $order->canceled_timestamp = strtotime(date('Y-m-d H:i:s'));
            } elseif ($status == 1) {
                $order->status = $status;
                $order->price = $request->price;
                $order->accept_timestamp = strtotime(date('Y-m-d H:i:s'));
            } elseif ($status == 2) {
                $order->started_timestamp = strtotime(date('Y-m-d H:i:s'));
                $order->status = $status;
            } else {
                $order->delivered_timestamp = strtotime(date('Y-m-d H:i:s'));
                $order->status = $status;
            }
            $order->save();
            return redirect()->back();
        }
        return redirect()->back();
    }
    public function detail($status, $order_id)
    {
        $pending = Order::where('status', 0)->count();
        $accept = Order::where('status', 1)->count();
        $start = Order::where('status', 2)->count();
        $delivered = Order::where('status', 3)->count();

        $order = Order::with(['user:uuid,name,image,email,verify'])->find($order_id);
        $order_count = Order::where('user_id', $order->user->uuid)->count();
        $order->user->order_count = $order_count;
        $answers = UserAnswer::where('order_id', $order->id)->get();
        foreach ($answers as $item) {
            if ($item->type == 'single_image' || $item->type == 'multi_images') {
                $item->answer = explode(',', $item->answer);
            }
        }
        $order->answers = $answers;

        return view('order.detail', compact('order','pending','accept','start','delivered'));
    }


    public function conversation($order_id)
    {
        $conversation = Message::where('order_id', $order_id)->orderBy('created_at', 'asc')
            ->get();
        $order = Order::find($order_id);
        $findUser = User::find($order->user_id)->first();
        return  view('order.chat', compact('conversation', 'findUser', 'order'));
    }

    public function sendMessage(Request $request)
    {
        $message = new Message();
        $message->order_id = $request->order_id;
        $message->user_id = $request->user_id;
        $message->send_by = 'admin';
        $message->message = $request->message ?: '';
        $message->type = 'text';
        $message->time = strtotime(date('Y-m-d H:i:s'));
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $mime = explode('/', $file->getClientMimeType());
            $filename = time() . '-' . uniqid() . '.' . $extension;
            if ($file->move('uploads/user/' . $request->order_id . '/messages/', $filename))
                $path = '/uploads/user/' . $request->order_id . '/messages/' . $filename;
            $message->attachment = $path;
        }
        $message->save();
        return response()->json($message);
    }
}
