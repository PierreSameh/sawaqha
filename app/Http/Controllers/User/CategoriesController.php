<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\Models\Category;

class CategoriesController extends Controller
{
    use HandleResponseTrait;

    public function get() {
        $categories = Category::where("isMainCat", true)->latest()->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                $categories
            ],
            []
        );
    }

    public function sub_categories(Request $request) {
        $categories = Category::find($request->category_id);
        if ($categories) {
            $sub_categories = $categories->sub_categories()->get();
            return $this->handleResponse(
                true,
                "عملية ناجحة",
                [],
                [
                    $sub_categories
                ],
                []
            );
        }
        return $this->handleResponse(
            false,
            "invalid id",
            [],
            [
            ],
            []
        );
    }

    public function search(Request $request) {
        $search = $request->search ? $request->search : '';
        $categories = Category::latest()->where('name', 'like', '%' . $search . '%')
        ->orWhere('description', 'like', '%' . $search . '%')->get();

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [
                $categories
            ],
            [
                "search" => "البحث بالاسم او المحتوي"
            ]
        );
    }
}
