<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function addCoupon(Request $request){
        $validator = Validator::make($request->all(), [
            "name"=> ["required","string","max:255"],
            "value"=> ["required","numeric"],
            ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $coupon = Coupon::create([
            "name"=> $request->name,
            "value"=> $request->value
            ]);
        return redirect()->back()->with("success","Coupon Added Successfully");

    }
}
