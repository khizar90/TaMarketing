<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function faqs()
    {
        $list = Faq::all();
        return response()->json([
            'status' => true,
            'action' =>  'Faqs',
            'data' => $list
        ]);
    }
}
