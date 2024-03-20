<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\Console\Question\Question;

class FormController extends Controller
{
    public function questions()
    {
        $verify = User::where('verify', 0)->count();
        $pending = Order::where('status', 0)->count();
        $accept = Order::where('status', 1)->count();
        $start = Order::where('status', 2)->count();
        $delivered = Order::where('status', 3)->count();

        $questions = Form::all();
        $single_image = Form::where('type', 'single_image')->first();
        $single_video = Form::where('type', 'single_video')->first();
        $multiple_images = Form::where('type', 'multiple_images')->first();
        $document_type = Form::where('type', 'document_type')->first();
        return view('form.index', compact('questions', 'single_image', 'single_video', 'multiple_images', 'document_type', 'pending', 'accept', 'start', 'delivered', 'pending', 'accept', 'start', 'delivered', 'verify'));
    }
    public function addQuestion(Request $request)
    {
        $create = new Form();
        $create->type = $request->type;
        $create->is_required = $request->is_required;
        $create->question = $request->question;
        $create->save();
        return redirect()->back();
    }

    public function deleteQuestion($id)
    {
        $find = Form::find($id);
        if ($find) {
            $find->delete();
            return redirect()->back();
        }
        return redirect()->back();
    }
    public function editQuestion(Request $request, $id)
    {
        $create = Form::find($id);
        $create->type = $request->type;
        $create->is_required = $request->is_required;
        $create->question = $request->question;
        $create->save();
        return redirect()->back();
    }

    public function addOptions(Request $request, $id)
    {

        $find = Form::find($id);
        if ($find) {

            $optionsArray = json_decode($request->options, true);
            $commaSeparatedOptions = implode(', ', array_column($optionsArray, 'value'));
            $find->options = $commaSeparatedOptions;
            $find->save();
            return redirect()->back();
        }
        return redirect()->back();
    }
   
}
