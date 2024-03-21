<?php

namespace App\Http\Controllers\Admin;

use App\Actions\FirebaseNotification;
use App\Actions\NewNotification;
use App\Http\Controllers\Controller;
use App\Models\CancelOrder;
use App\Models\Message;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserDevice;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function list($status)
    {
        $verify = User::where('verify', 0)->count();
        $pending = Order::where('status', 0)->count();
        $accept = Order::where('status', 1)->count();
        $start = Order::where('status', 2)->count();
        $delivered = Order::where('status', 3)->count();

        $orders = Order::with(['user:uuid,name,image,email,verify'])->where('status', $status)->latest()->get();
        return view('order.index', compact('orders', 'pending', 'accept', 'start', 'delivered', 'verify'));
    }
    public function changeStatus(Request $request, $order_id, $status)
    {
        $order = Order::find($order_id);
        if ($order) {
            $to = User::find($order->user_id);

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

                $to = User::find($order->user_id);

                NewNotification::handle($to, $order->id, 'Your order #' . $order->id . ' has been accepted by the admin. Please proceed with the next payment.', 'accepted');
                $tokens = UserDevice::where('user_id', $order->user_id)->where('token', '!=', '')->groupBy('token')->pluck('token')->toArray();
                FirebaseNotification::handle($tokens, 'Your order #' . $order->id . ' has been accepted by the admin. Please proceed with the next payment.', 'Order Accepted', ['data_id' => $order->id, 'type' => 'accepted']);
            } elseif ($status == 2) {
                $order->started_timestamp = strtotime(date('Y-m-d H:i:s'));
                $order->status = $status;

                NewNotification::handle($to, $order->id, 'Your order #' . $order->id . ' has been started. Stay tuned for updates!.', 'started');
                $tokens = UserDevice::where('user_id', $order->user_id)->where('token', '!=', '')->groupBy('token')->pluck('token')->toArray();
                FirebaseNotification::handle($tokens, 'Your order #' . $order->id . ' has been started. Say tuned for updates!.', 'Order Started', ['data_id' => $order->id, 'type' => 'started']);
            } elseif ($status == 3) {
                $order->delivered_timestamp = strtotime(date('Y-m-d H:i:s'));
                $order->status = $status;
                NewNotification::handle($to, $order->id, 'You have a delivery request for the order #' . $order->id . '. Please accept it and complete the order.', 'delivered');
                $tokens = UserDevice::where('user_id', $order->user_id)->where('token', '!=', '')->groupBy('token')->pluck('token')->toArray();
                FirebaseNotification::handle($tokens, 'You have a delivery request for the order #' . $order->id . '. Please accept it and complete the order.', 'Order Delivered', ['data_id' => $order->id, 'type' => 'delivered']);
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
        $verify = User::where('verify', 0)->count();
        $pending = Order::where('status', 0)->count();
        $accept = Order::where('status', 1)->count();
        $start = Order::where('status', 2)->count();
        $delivered = Order::where('status', 3)->count();

        $order = Order::with(['user:uuid,name,image,email,verify'])->find($order_id);
        $order_count = Order::where('user_id', $order->user->uuid)->count();
        $order->user->order_count = $order_count;
        $answers = UserAnswer::where('order_id', $order->id)->get();
        foreach ($answers as $item) {
            $item->answer = explode(',', $item->answer);
        }
        $order->answers = $answers;

        $payment = Payment::where('order_id', $order_id)->latest()->first();
        $order->payment = $payment;

        return view('order.detail', compact('order', 'pending', 'accept', 'start', 'delivered', 'verify'));
    }


    public function conversation($order_id)
    {
        $verify = User::where('verify', 0)->count();
        $pending = Order::where('status', 0)->count();
        $accept = Order::where('status', 1)->count();
        $start = Order::where('status', 2)->count();
        $delivered = Order::where('status', 3)->count();

        $conversation = Message::where('order_id', $order_id)->orderBy('created_at', 'asc')
            ->get();
        $order = Order::find($order_id);
        $findUser = User::find($order->user_id)->first();
        return  view('order.chat', compact('conversation', 'findUser', 'order', 'pending', 'accept', 'start', 'delivered', 'verify'));
    }

    public function sendMessage(Request $request)
    {
        $message = new Message();
        $message->order_id = $request->order_id;
        $message->user_id = $request->user_id;
        $message->send_by = 'admin';
        $message->message = $request->message ?: '';
        $message->time = strtotime(date('Y-m-d H:i:s'));
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $mime = explode('/', $file->getClientMimeType());
            $filename = time() . '-' . uniqid() . '.' . $extension;
            if ($file->move('uploads/user/' . $request->order_id . '/messages/', $filename))
                $path = '/uploads/user/' . $request->order_id . '/messages/' . $filename;
            $message->attachment = $path;
            $message->type = 'image';
        } else {
            $message->type = 'text';
        }
        $message->save();
        return response()->json($message);
    }
}
