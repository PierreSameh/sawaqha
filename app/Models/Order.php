<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "recipient_name",
        "recipient_phone",
        "recipient_address",
        "sub_total",
        "total_sell_price",
        "user_type",
        "status",
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
