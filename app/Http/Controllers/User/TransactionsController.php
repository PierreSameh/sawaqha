<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\SendEmailTrait;
use App\HandleResponseTrait;

class TransactionsController extends Controller
{
    use HandleResponseTrait, SendEmailTrait;

    public function transactionsAll(Request $request) {
        $user = $request->user();
        $type = $request->type;
        $order = $user->transactions()->latest()->with(["user" => function ($q) {
            $q->only("name", "email", "phone", "user_type", "picture", "is_email_verified", "is_phone_verified");
        }])->when($request->type, function ($q) use ($type) {
            $q->where("type",  $type);
        })->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [$order],
            [
                "parameters" => [
                    "type" => [
                        1 => "بيع ",
                        2 => "سحب",
                    ]
                ]
            ]
        );
    }

    public function transactionsPagination(Request $request) {
        $per_page = $request->per_page ? $request->per_page : 10;

        $user = $request->user();
        $type = $request->type;
        $order = $user->transactions()->latest()->with(["user" => function ($q) {
            $q->only("name", "email", "phone", "user_type", "picture", "is_email_verified", "is_phone_verified");
        }])->when($request->type, function ($q) use ($type) {
            $q->where("type",  $type);
        })->paginate($per_page);

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [$order],
            [
                "parameters" => [
                    "type" => [
                        1 => "بيع ",
                        2 => "سحب",
                    ]
                ]
            ]
        );
    }
}
