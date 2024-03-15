<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\VerifyEmail;
use App\Models\Faq;
use App\Models\Order;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function index()
    {

        $verify = User::where('verify', 0)->count();

        $pending = Order::where('status', 0)->count();
        $accept = Order::where('status', 1)->count();
        $start = Order::where('status', 2)->count();
        $delivered = Order::where('status', 3)->count();
        $complete = Order::where('status', 4)->count();
        $canceled = Order::where('status', 5)->count();

        $total = User::count();
        $todayActive = 0;

        $todayNew = User::whereDate('created_at', date('Y-m-d'))->count();
        $mainUsers = User::pluck('uuid');
        $loggedIn = UserDevice::whereIn('user_id', $mainUsers)->where('token', '!=', '')->distinct('user_id')->count();

        $iosTraffic = UserDevice::whereIn('user_id', $mainUsers)->where('device_name', 'ios')->count();
        $androidTraffic = UserDevice::whereIn('user_id', $mainUsers)->where('device_name', 'android')->count();

        return view('index', compact('todayActive', 'total', 'todayNew', 'mainUsers', 'loggedIn', 'iosTraffic', 'androidTraffic', 'pending', 'accept', 'start', 'delivered', 'complete', 'canceled', 'verify'));
    }

    public function users(Request $request)
    {
        $verify = User::where('verify', 0)->count();
        $pending = Order::where('status', 0)->count();
        $accept = Order::where('status', 1)->count();
        $start = Order::where('status', 2)->count();
        $delivered = Order::where('status', 3)->count();

        $users = User::where('verify', 1)->latest()->paginate(20);

        foreach ($users as $user) {
                
            $userpending = Order::where('user_id', $user->uuid)->where('status', 0)->count();
            $user->userpending=$userpending;
            $useraccepted = Order::where('user_id', $user->uuid)->where('status', 1)->count();
            $user->useraccepted=$useraccepted;
            $userstarted = Order::where('user_id', $user->uuid)->where('status', 2)->count();
            $user->userstarted=$userstarted;
            $userdelivered = Order::where('user_id', $user->uuid)->where('status', 3)->count();
            $user->userdelivered=$userdelivered;
            $usercompleted = Order::where('user_id', $user->uuid)->where('status', 4)->count();
            $user->usercompleted=$usercompleted;
            $userrcancelled = Order::where('user_id', $user->uuid)->where('status', 5)->count();
            $user->userrcancelled=$userrcancelled;
        }




        if ($request->ajax()) {
            $query = $request->input('query');
            $users = User::query();
            if ($query) {
                $users = $users->where('email', 'like', '%' . $query . '%')->where('verify', 1);
            }
            $users = $users->where('verify', 1)->latest()->Paginate(20);
           
            foreach ($users as $user) {
                
                $userpending = Order::where('user_id', $user->uuid)->where('status', 0)->count();
                $user->userpending=$userpending;
                $useraccepted = Order::where('user_id', $user->uuid)->where('status', 1)->count();
                $user->useraccepted=$useraccepted;
                $userstarted = Order::where('user_id', $user->uuid)->where('status', 2)->count();
                $user->userstarted=$userstarted;
                $userdelivered = Order::where('user_id', $user->uuid)->where('status', 3)->count();
                $user->userdelivered=$userdelivered;
                $usercompleted = Order::where('user_id', $user->uuid)->where('status', 4)->count();
                $user->usercompleted=$usercompleted;
                $userrcancelled = Order::where('user_id', $user->uuid)->where('status', 5)->count();
                $user->userrcancelled=$userrcancelled;
            }
            return view('user.user-ajax', compact('users', 'pending', 'accept', 'start', 'delivered', 'verify'));
        }

        return view('user.index', compact('users', 'pending', 'accept', 'start', 'delivered', 'verify'));
    }


    public function exportCSV(Request $request)
    {

        $users = User::select('name', 'email')->where('verify', 1)->get();

        $columns = ['name', 'email'];
        $handle = fopen(storage_path('users.csv'), 'w');

        fputcsv($handle, $columns);

        foreach ($users->chunk(2000) as $chunk) {
            foreach ($chunk as $user) {
                fputcsv($handle, $user->toArray());
            }
        }

        fclose($handle);

        return response()->download(storage_path('users.csv'))->deleteFileAfterSend(true);
    }

    public function verifyUsers(Request $request)
    {
        $verify = User::where('verify', 0)->count();
        $pending = Order::where('status', 0)->count();
        $accept = Order::where('status', 1)->count();
        $start = Order::where('status', 2)->count();
        $delivered = Order::where('status', 3)->count();

        $users = User::where('verify', 0)->latest()->paginate(20);

        if ($request->ajax()) {
            $query = $request->input('query');
            $users  = User::query();
            if ($query) {

                $users = $users->where('email', 'like', '%' . $query . '%')->where('verify', 0);
            }
            $users = $users->where('verify', 0)->latest()->Paginate(20);

            return view('user.verify_ajax', compact('users', 'pending', 'accept', 'start', 'delivered', 'verify'));
        }

        return view('user.verify_request', compact('users', 'pending', 'accept', 'start', 'delivered', 'verify'));
    }


    public function getVerify($user_id)
    {

        $user = User::find($user_id);
        if ($user) {
            $user->verify = 1;
            $user->save();
            $mail_details = [
                'body' => $user->name,
            ];


            Mail::to($user->email)->send(new VerifyEmail($mail_details));


            return redirect()->back();
        }
        return redirect()->back();
    }

    public function usersDelete($user_id)
    {
        $user = User::find($user_id);
        if ($user) {
            $user->delete();
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function faqs()
    {
        $verify = User::where('verify', 0)->count();
        $pending = Order::where('status', 0)->count();
        $accept = Order::where('status', 1)->count();
        $start = Order::where('status', 2)->count();
        $delivered = Order::where('status', 3)->count();
        $faqs = Faq::all();

        return view('faq', compact('faqs', 'pending', 'accept', 'start', 'delivered', 'verify'));
    }

    public function deleteFaq($id)
    {
        $faq  = Faq::find($id);
        $faq->delete();
        return redirect()->back();
    }

    public function addFaq(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $faq = new Faq();
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();
        return redirect()->back();
    }

    public function editFaq(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $faq = Faq::find($id);
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();
        return redirect()->back();
    }
}
