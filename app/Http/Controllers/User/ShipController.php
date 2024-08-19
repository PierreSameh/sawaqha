<?php

namespace App\Http\Controllers\User;

use App\HandleResponseTrait;
use App\Http\Controllers\Controller;
use App\Models\ShipRate;
use Illuminate\Http\Request;

class ShipController extends Controller
{
    use HandleResponseTrait;

    public function get() {
        $rates = ShipRate::all();
        return $this->handleResponse(
            true,
            "",
            [],
            [
                $rates
            ],
            []
        );
    }
}
