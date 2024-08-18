<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;
class SocialController extends Controller
{
    public function addSocial(Request $request){
        $validator = Validator::make($request->all(), [
            "whatsapp"=> "nullable|string",
            "facebook"=> "nullable|string",
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $social = Setting::where("id", 1)->first();
        if($request->whatsapp){
            $social->whatsapp = $request->whatsapp;
            $social->save();
        }
        if($request->facebook){
            $social->facebook = $request->facebook;
            $social->save();
        }
        return redirect()->back()->with("success","Added Successfully");

    }
}
