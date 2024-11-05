<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "your_name",
        "your_phone",
        "your_sec_phone",
        "recipient_name",
        "recipient_phone",
        "recipient_second_phone",
        "recipient_governorate",
        "recipient_address",
        "sub_total",
        "total_sell_price",
        "user_type",
        "status",
        "facebook",
        "web_page",
        "notes",
        "shipping",
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Ordered_Product', 'order_id');
    }

}
