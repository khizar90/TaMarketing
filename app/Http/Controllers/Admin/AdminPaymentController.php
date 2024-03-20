<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Venmo;
use App\Models\Zelle;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function venmo()
    {
        $verify = User::where('verify', 0)->count();
        $pending = Order::where('status', 0)->count();
        $accept = Order::where('status', 1)->count();
        $start = Order::where('status', 2)->count();
        $delivered = Order::where('status', 3)->count();
        $venmo = Venmo::latest()->first();
        return view('venmo.index', compact('pending', 'accept', 'start', 'delivered', 'verify', 'venmo'));
    }
    public function addVenmo(Request $request)
    {
        $create = new Venmo();
        $image = null; // Initialize $image variable

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $mime = explode('/', $file->getClientMimeType());
            $filename = time() . '-' . uniqid() . '.' . $extension;
            if ($file->move('uploads/QRimage/', $filename)) {
                $image = '/uploads/QRimage/' . $filename;
            }
        }
        $create->image = $image;
        $create->save();

        return redirect()->back();
    }
    public function deleteVenmo($id)
    {
        $find = Venmo::find($id);
        if ($find) {
            $find->delete();
            return redirect()->back();
        }
        return redirect()->back();
    }

    public function editVenmo(Request $request, $id){
        $edit= Venmo::find($id);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $mime = explode('/', $file->getClientMimeType());
            $filename = time() . '-' . uniqid() . '.' . $extension;
            if ($file->move('uploads/QRimage/', $filename)) {
                $image = '/uploads/QRimage/' . $filename;
            }
        }
        $edit->image = $image;
        
        $edit->save();
        return redirect()->back();
    }


    public function zelle()
    {
        $verify = User::where('verify', 0)->count();
        $pending = Order::where('status', 0)->count();
        $accept = Order::where('status', 1)->count();
        $start = Order::where('status', 2)->count();
        $delivered = Order::where('status', 3)->count();
        $zelle= Zelle::latest()->first();
        return view('zelle.index', compact('pending', 'accept', 'start', 'delivered', 'verify','zelle'));
    }
    public function addZelle(Request $request)
    {
        $create = new Zelle();
        $create->name = $request->name;
        $create->phonenumber = $request->phonenumber;
        $create->save();
        return redirect()->back();
    }
    public function deleteZelle($id)
    {
        $find = Zelle::find($id);
        if ($find) {
            $find->delete();
            return redirect()->back();
        }
        return redirect()->back();
    }
    public function editZelle(Request $request, $id){
        $edit= Zelle::find($id);
        $edit->name= $request->name;
        $edit->phonenumber = $request->phonenumber;
        $edit->save();
        return redirect()->back();
    }
}
