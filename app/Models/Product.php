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
        "sec_description",
        "quantity",
        "price",
        "wholesale_price",
        "least_quantity_wholesale",
        "isDiscounted",
        "category_id",
    ];

    public function gallery()
    {
        return $this->hasMany(Gallery::class);
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
    public function orders()
    {
        return $this->hasMany('App\Models\Ordered_Product', 'product_id');
    }

    public function sizes()
    {
        return $this->hasMany('App\Models\Size', 'product_id');
    }

    public function colors()
    {
        return $this->hasMany('App\Models\Color', 'product_id');
    }
}
