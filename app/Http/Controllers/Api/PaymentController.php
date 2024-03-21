<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentRequest;
use App\Models\Payment;
use App\Models\Venmo;
use App\Models\Zelle;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(PaymentRequest $request)
    {
        $create = new Payment();
        $create->user_id = $request->user_id;
        $create->order_id = $request->order_id;
        $create->method = $request->method;

        if ($request->method == 'venmo' || $request->method == 'zelle') {
            if ($request->hasFile('transcript')) {
                $file = $request->file('transcript');
                // $path = Storage::disk('s3')->putFile('user/' . $request->user_id . '/profile', $file);
                // $path = Storage::disk('s3')->url($path);
                $extension = $file->getClientOriginalExtension();
                $mime = explode('/', $file->getClientMimeType());
                $filename = time() . '-' . uniqid() . '.' . $extension;
                if ($file->move('uploads/user/' . $request->user_id . '/' . $request->order_id . '/transcript/', $filename))
                    $path = '/uploads/user/' . $request->user_id . '/' . $request->order_id . '/transcript/' . $filename;
                $create->transcript = $path;
            }
        }
        else{

        }

        $create->save();


        return response()->json([
            'status' => true,
            'action' => "Payment Send"
        ]);
    }

    public function method($type){
        if($type == 'venmo'){
        }
        if($type == 'zelle'){
        }
        return response()->json([
            'status' => true,
            'action' => "Method",
            'data' => $method
        ]);
    }
}
