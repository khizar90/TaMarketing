<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaymentRequest;
use App\Models\Payment;
use App\Models\User;
use App\Models\Venmo;
use App\Models\Zelle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\Stripe;

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
            $create->payment_id = $request->payment_id;
        }

        $create->save();


        return response()->json([
            'status' => true,
            'action' => "Payment Send"
        ]);
    }

    public function createIntent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,uuid',
            'amount' => 'required',
        ]);
        $errorMessage = implode(', ', $validator->errors()->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'action' =>  $errorMessage,
            ]);
        }
        $amount  = $request->amount * 100;


        $user = User::where('uuid', $request->user_id)->first();
        if ($user) {
            $stripeId = null;
            if ($user->stripe_customer_id)
                $stripeId = $user->stripe_customer_id;
            // Stripe::setApiKey(config(key: 'app.stripe_secret'));
            Stripe::setApiKey('sk_test_51OmS6lHfMxxkFB0C0z5BKgO9U36rj425iujp12x17H6EJeYXLBL9Tl4wkpJDoAeOsF0JckMeKlmyPMogdAMWD3VN00037wktut');

            if ($user->stripe_customer_id === '') {

                $person = Customer::create([
                    'email' => $user->email,
                    'name' => $user->name,
                    'description' => ''
                ]);
                if (!$stripeId) {
                    $stripeId = $person['id'];
                    User::where('uuid', $user->uuid)->update(['stripe_customer_id' => $stripeId]);
                }
            }
            $user = User::find($request->user_id);
            $intent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'customer' => $user->stripe_customer_id
            ]);

         
            return response()->json([
                'status' => true,
                'action' =>  'Intent Created',
                'data' => $intent->client_secret
            ]);
        }
    }
}
