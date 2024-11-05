<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        $users = User::latest()->paginate(15);
        return view('Admin.users.all', compact('users'));
    }

    public function getUser($userId){
        $user = User::find($userId);
        $orders = Order::where('user_id', $userId)->paginate(10);
        return view('Admin.users.get', compact('user', 'orders'));
    }
}
