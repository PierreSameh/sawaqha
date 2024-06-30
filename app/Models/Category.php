<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "category_id",
        "isMainCat",
        "description",
        "thumbnail_path",
    ];

    public $table = "categories";

    public function products()
    {
        return $this->hasMany('App\Models\Product', 'category_id');
    }


    public function sub_categories()
    {
        return $this->hasMany('App\Models\Category', 'category_id');
    }

}
