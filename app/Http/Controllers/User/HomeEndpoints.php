<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\Wishlist;
use App\Models\Category;
use App\Models\Banner;
use Exception;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class HomeEndpoints extends Controller
{
    use HandleResponseTrait;

    public function addIsFavKey($products, $authorization) {
        $user = null;

        $authorizationHeader = $authorization;

        if ($authorizationHeader) {
            try {
                // Extract token from the header (assuming 'Bearer' prefix)
                $hashedTooken = str_replace('Bearer ', '', $authorizationHeader);
                $token = PersonalAccessToken::findToken($hashedTooken);
                $user = $token?->tokenable;

            } catch (Exception $e) {
                // Handle potential exceptions during token validation
                // Log the error or return an appropriate response
            }
        }

        if ($user) {
            $user_id = $user->id;

            // Add isFav key to each product
            $products->each(function ($product) use ($user_id) {
                $product->isFav = Wishlist::where('user_id', $user_id)->where('product_id', $product->id)->exists();
            });
        } else {
            // Add isFav key to each product as false if not logged in
            $products->each(function ($product) {
                $product->isFav = false;
            });
        }
        return $products;
    }

    public function getCategories() {
        return $categories = Category::limit(10)->get();
    }

    public function getLatestBanners() {
        return $banners = Banner::all();
    }

    public function getLatestProducts($token) {
        $products = Product::latest()->with("gallery")->limit(15)->get();

        return $products = $this->addIsFavKey($products, $token);
    }

    public function getMostSelled($token) {

        $completedOrders = Order::with("products")->where("status", 4)->get();
        $topProducts = Product::
        with("gallery")
        ->withCount('orders')
        ->orderBy('orders_count', 'desc')
        ->limit(10)
        ->get();

        $topProducts = $this->addIsFavKey( $topProducts, $token);

        return $topProducts;
    }
    public function getDiscountedProducts($token) {

        $discountedProducts = Product::
        with("gallery")->
        where("isDiscounted", true)
        ->limit(10)
        ->get();

        $topProducts = $this->addIsFavKey( $discountedProducts, $token);

        return $discountedProducts;
    }

    public function getHomeApi(Request $request) {
        return $this->handleResponse(
            true,
            "Success",
            [],
            [
                "banners" => $this->getLatestBanners(),
                "latest_categories" => $this->getCategories(),
                "latest_products" => $this->getLatestProducts($request->header('Authorization')),
                "most_selled" => $this->getMostSelled($request->header('Authorization')),
                "discounted" => $this->getDiscountedProducts($request->header('Authorization'))
            ],
            []
        );
    }

    public function downloadImage(Request $request)
    {
        $url = $request->query('url');

        if (!$url) {
            return response()->json(['error' => 'URL is required'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $content = $response->body();
                $contentType = $response->header('Content-Type');

                return response($content, Response::HTTP_OK)
                    ->header('Content-Type', $contentType)
                    ->header('Content-Disposition', 'attachment; filename="downloaded_image.' . $this->getExtension($contentType) . '"');
            } else {
                return response()->json(['error' => 'Failed to download image'], Response::HTTP_BAD_REQUEST);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function getExtension($contentType)
    {
        $mimeTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            // Add more MIME types as needed
        ];

        return $mimeTypes[$contentType] ?? 'jpg';
    }
}
