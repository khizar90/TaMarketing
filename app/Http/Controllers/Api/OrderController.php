<?php

namespace App\Http\Controllers\Api;

use App\Actions\FirebaseNotification;
use App\Actions\NewNotification;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CancelOrderRequest;
use App\Http\Requests\Api\CreateOrderRequest;
use App\Http\Requests\Api\SendMessageRequest;
use App\Models\CancelOrder;
use App\Models\Form;
use App\Models\Message;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserAnswer;
use App\Models\UserDevice;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function question()
    {
        $questions = Form::all();
        foreach ($questions as $item) {

            if ($item->options != '') {
                $options = explode(',', $item->options);
                $item->options = $options;
            } else {
                $item->options = [];
            }
        }
        return response()->json([
            'status' => true,
            'action' => 'Question',
            'data' => $questions
        ]);
    }
    public function create(CreateOrderRequest $request)
    {
        $order = new Order();
        $order->user_id = $request->json('user_id');
        $order->placed_timestamp = strtotime(date('Y-m-d H:i:s'));
        $order->save();
        $questions = $request->json('questions');
        foreach ($questions as $item) {
            $create = new UserAnswer();
            $create->user_id = $request->json('user_id');
            $create->question = $item['question'];
            $create->type = $item['type'];
            $create->order_id = $order->id;
            if ($item['type'] == 'multiple_images' || $item['type'] == 'single_image') {
                $mediaPaths = [];
                foreach ($item['answer'] as $base64Image) {
                    $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));

                    // Generate a unique filename
                    $filename = uniqid() . '.jpg'; // You may adjust the extension based on the actual image type

                    $path = '/uploads/order/media/' . $filename;
                    if (file_put_contents(public_path($path), $decodedImage)) {
                        $mediaPaths[] =  $path;
                    }
                }

                $create->answer = implode(',', $mediaPaths);
            } elseif ($item['type'] == 'single_video') {
                $mediaPaths = [];
                foreach ($item['answer'] as $base64Video) {
                    $decodedVideo = base64_decode(preg_replace('#^data:video/\w+;base64,#i', '', $base64Video));
                    $videoFilename = uniqid() . '.mp4'; // You may adjust the extension based on the actual video type
                    $videoPath = '/uploads/order/media/' . $videoFilename;
                    if (file_put_contents(public_path($videoPath), $decodedVideo)) {
                        $mediaPaths[] = $videoPath;
                    }
                }
                $create->answer = implode(',', $mediaPaths);
            } elseif ($item['type'] == 'document_type') {
                $mediaPaths = [];
                foreach ($item['answer'] as $base64Pdf) {
                    $decodedDocument = base64_decode(preg_replace('#^data:application/\w+;base64,#i', '', $base64Pdf));
                    $documentFilename = uniqid() . '.pdf'; // You may adjust the extension based on the actual document type
                    $documentPath = '/uploads/order/media/' . $documentFilename;
                    if (file_put_contents(public_path($documentPath), $decodedDocument)) {
                        $mediaPaths[] = $documentPath;
                    }
                }
                $create->answer = implode(',', $mediaPaths);
            } else {
                $create->answer = implode(',', $item['answer']);
            }

            $create->save();
        }
        return response()->json([
            'status' => true,
            'action' => "Order Placed",
        ]);
    }


    public function list($status, $user_id)
    {
        $list = Order::where('user_id', $user_id)->where('status', $status)->get();
        foreach ($list as $item) {
            $paymnet  = Payment::where('order_id', $item->id)->first();
            if ($paymnet) {
                $item->is_payment = true;
            } else {
                $item->is_payment = false;
            }
        }
        return response()->json([
            'status' => true,
            'action' => "Orders",
            'data' => $list
        ]);
    }
    public function cancel(CancelOrderRequest $request)
    {
        $order = Order::find($request->order_id);
        $cancel = new CancelOrder();
        $cancel->order_id = $request->order_id;
        $cancel->reason = $request->reason;
        $cancel->cancel_by = 'user';
        $cancel->save();
        $order->status = 5;
        $order->canceled_timestamp = strtotime(date('Y-m-d H:i:s'));
        $order->save();

        return response()->json([
            'status' => true,
            'action' => "Order Canceled",
        ]);
    }

    public function detail($order_id)
    {

        $order = Order::with(['user:uuid,name,image,email,verify'])->find($order_id);
        if ($order) {
            $answers = UserAnswer::where('order_id', $order_id)->get();
            foreach ($answers as $item) {
                $item->answer = explode(',', $item->answer);
            }
            $paymnet  = Payment::where('order_id', $order_id)->first();
            if ($paymnet) {
                $order->is_payment = true;
            } else {
                $order->is_payment = false;
            }
            $order->un_read = Message::where('order_id', $order_id)->where('send_by','admin')->where('is_read', 0)->count();
            $order->answers = $answers;
            return response()->json([
                'status' => true,
                'action' => "Order Detail",
                'data' => $order
            ]);
        }
        return response()->json([
            'status' => false,
            'action' => "Order not found",
        ]);
    }
    public function complete($order_id)
    {
        $order = Order::find($order_id);
        if ($order) {
            $order->status = 4;
            $order->complete_timestamp = strtotime(date('Y-m-d H:i:s'));
            $order->save();

            $to = User::find($order->user_id);
            NewNotification::handle($to, $order->id, 'Your order #' . $order->id . 'hase been completed successfully.', 'completed');
            $tokens = UserDevice::where('user_id', $order->user_id)->where('token', '!=', '')->groupBy('token')->pluck('token')->toArray();
            FirebaseNotification::handle($tokens, 'Your order #' . $order->id . ' hase been completed successfully.', 'Order Completed', ['data_id' => $order->id, 'type' => 'completed']);


            return response()->json([
                'status' => true,
                'action' => "Order Complete",
            ]);
        }

        return response()->json([
            'status' => false,
            'action' => "Order not found",
        ]);
    }

    public function ordersChat($user_id)
    {
        $ordersIds = Message::where('user_id', $user_id)
            ->groupBy('order_id')
            ->orderByDesc('created_at')
            ->pluck('order_id');

        $orders = [];
        foreach ($ordersIds as $item) {
            $order = Order::find($item);
            $count = Message::where('order_id', $order->id)->where('send_by','admin')->where('is_read', 0)->count();
            $order->un_read  = $count;
            $paymnet  = Payment::where('order_id', $order->id)->first();
            if ($paymnet) {
                $order->is_payment = true;
            } else {
                $order->is_payment = false;
            }
            $orders[] = $order;
        }


        return response()->json([
            'status' => true,
            'action' => "Order Chat",
            'data' => $orders
        ]);
    }
    public function conversation($order_id)
    {
        Message::where('order_id', $order_id)->where('send_by','admin')->where('is_read', 0)->update(['is_read' => 1]);
        $messages = Message::where('order_id', $order_id)->latest()->get();
        return response()->json([
            'status' => true,
            'action' => "Messages",
            'data' => $messages
        ]);
    }

    public function sendMessage(SendMessageRequest $request)
    {
        $message = new Message();
        $message->user_id = $request->user_id;
        $message->order_id = $request->order_id;
        $message->type = $request->type;
        $message->message = $request->message ?: '';
        $message->send_by = 'user';
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

        $newMessage = Message::find($message->id);
        return response()->json([
            'status' => true,
            'action' => "Message Send",
            'data' => $newMessage
        ]);
    }
}
