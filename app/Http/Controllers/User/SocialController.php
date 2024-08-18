<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\HandleResponseTrait;

class SocialController extends Controller
{
    use HandleResponseTrait;
    public function getSocial(){
        $social = Setting::first();
        if ($social){
            return $this->handleResponse(
                true,
                "",
                [],
                [
                    "whatsapp_suggestion"=> $social->whatsapp,
                    "whatsaap_wholesale"=> $social->facebook
                ],
                []
            );
        }
        return $this->handleResponse(
            true,
            "No Social Links Available At The Moment",
            [],
            [],
            []
            );
    }
}
