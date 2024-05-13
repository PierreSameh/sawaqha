<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        "user_id",
        "order_id",
        "type",
        "amount",
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
