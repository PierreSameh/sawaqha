<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "main_image",
        "description",
        "quantity",
        "price",
        "wholesale_price",
        "least_quantity_wholesale",
        "isDiscounted",
        "category_id",
    ];

    public function gallery()
    {
        return $this->hasMany('App\Models\Gallery', 'product_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
    public function orders()
    {
        return $this->hasMany('App\Models\Ordered_Product', 'product_id');
    }


}
