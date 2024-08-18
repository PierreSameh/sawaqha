<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HandleResponseTrait;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    use HandleResponseTrait;

    public function addProductToCart(Request $request) {
        $validator = Validator::make($request->all(), [
            "product_id" => ["required"],
            "quantity" => ["numeric"],
        ]);

        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                "",
                [$validator->errors()->first()],
                [],
                [
                    "في حالة كان المنتج موجود في عربة المستخم فالكمية تزداد بواحد او بالعدد المرسل من المستخدم في ال quantity"
                ]
            );
        }

        $product = Product::find($request->product_id);
        $quantity = $request->quantity ? $request->quantity : 1;
        $color = $request->input('color', null);
        $size = $request->input('size', null);
        $colors = Color::where("product_id", $product->id)->get();
        $sizes = Size::where("product_id", $product->id)->get();

        if ($product) {
            $user = $request->user();
            $product_if_in_user_cart = $user->cart()->where("product_id", $request->product_id)->first();
            if ($product_if_in_user_cart) {
                $prod_quantity = (int) $product_if_in_user_cart->quantity + (int) $quantity;
                if ((int) $prod_quantity > (int) $product->quantity)
                    return $this->handleResponse(
                        true,
                        "هذه الكمية غير متوفرة من المنتج",
                        [],
                        [],
                        [
                            "في حالة كان المنتج موجود في عربة المستخم فالكمية تزداد بواحد او بالعدد المرسل من المستخدم في ال quantity",
                            "اذا كان المنتج يحتوي على صور أو احجام ارسل البيانات مع المنتج"
                        ]
                    );
                if (isset($colors)) {
                    $product_if_in_user_cart->color = $request->color;
                }
                if (isset($sizes)) {
                    $product_if_in_user_cart->size = $request->size;
                }

                $product_if_in_user_cart->quantity = $prod_quantity;
                $product_if_in_user_cart->save();

                if ($product_if_in_user_cart)
                    return $this->handleResponse(
                        true,
                        "تم اضافة المنتج للعربة بنجاح",
                        [],
                        [],
                        [
                            "في حالة كان المنتج موجود في عربة المستخم فالكمية تزداد بواحد او بالعدد المرسل من المستخدم في ال quantity",
                            "اذا كان المنتج يحتوي على صور أو احجام ارسل البيانات مع المنتج"

                        ]
                    );
            } else {
                if ((int) $quantity > (int) $product->quantity)
                    return $this->handleResponse(
                        true,
                        "هذه الكمية غير متوفرة من المنتج",
                        [],
                        [],
                        [
                            "في حالة كان المنتج موجود في عربة المستخم فالكمية تزداد بواحد او بالعدد المرسل من المستخدم في ال quantity",
                            "اذا كان المنتج يحتوي على صور أو احجام ارسل البيانات مع المنتج"

                        ]
                    );


                $cart_item = Cart::create([
                    "user_id" => $user->id,
                    "product_id" => $product->id,
                    "color" => $color,
                    "size" => $size,
                    "quantity" => $quantity
                ]);

                if ($cart_item)
                    return $this->handleResponse(
                        true,
                        "تم اضافة المنتج للعربة بنجاح",
                        [],
                        [],
                        [
                            "في حالة كان المنتج موجود في عربة المستخم فالكمية تزداد بواحد او بالعدد المرسل من المستخدم في ال quantity"
                        ]
                    );
            }
        } else {
            return $this->handleResponse(
                false,
                "",
                ["هذا المنتج غير متاح"],
                [],
                [
                    "في حالة كان المنتج موجود في عربة المستخم فالكمية تزداد بواحد او بالعدد المرسل من المستخدم في ال quantity"
                ]
            );
        }

    }

    public function removeProductFromCart(Request $request) {
        $validator = Validator::make($request->all(), [
            "product_id" => ["required"],
        ]);

        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                "",
                [$validator->errors()->first()],
                [],
                []
            );
        }

        $product = Product::find($request->product_id);

        if ($product) {
            $user = $request->user();
            $product_if_in_user_cart = $user->cart()->where("product_id", $product->id)->first();
            if ($product_if_in_user_cart)
                $product_if_in_user_cart->delete();

            return $this->handleResponse(
                true,
                "تم حذف المنتج من العربة بنجاح",
                [],
                [],
                []
            );
        } else {
            return $this->handleResponse(
                false,
                "",
                ["هذا المنتج غير متاح"],
                [],
                [
                    "في حالة كان المنتج موجود في عربة المستخم فالكمية تزداد بواحد او بالعدد المرسل من المستخدم في ال quantity"
                ]
            );
        }
    }

    public function updateProductQuantityAtCart(Request $request) {
        $validator = Validator::make($request->all(), [
            "product_id" => ["required"],
            "quantity" => ["required", "numeric"],
        ]);

        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                "",
                [$validator->errors()->first()],
                [],
                []
            );
        }

        $product = Product::find($request->product_id);
        $quantity = $request->quantity;

        if ($product) {
            $user = $request->user();
            $product_if_in_user_cart = $user->cart()->where("product_id", $request->product_id)->first();
            if ($product_if_in_user_cart) {
                if ((int) $quantity > (int) $product->quantity)
                    return $this->handleResponse(
                        true,
                        "هذه الكمية غير متوفرة من المنتج",
                        [],
                        [],
                        []
                    );

                $product_if_in_user_cart->quantity = $quantity;
                $product_if_in_user_cart->save();

                if ($product_if_in_user_cart)
                    return $this->handleResponse(
                        true,
                        "تم تحديث العدد بنجاح",
                        [],
                        [],
                        []
                    );
            } else {
                return $this->handleResponse(
                    false,
                    "",
                    ["هذا المنتج غير متاح لديك في العربة"],
                    [],
                    []
                );
            }
        } else {
            return $this->handleResponse(
                false,
                "",
                ["هذا المنتج غير متاح"],
                [],
                [
                    "في حالة كان المنتج موجود في عربة المستخم فالكمية تزداد بواحد او بالعدد المرسل من المستخدم في ال quantity"
                ]
            );
        }

    }


    public function updateProductPriceAtCart(Request $request){
        $validator = Validator::make($request->all(), [
            "product_id" => ["required"],
            "sell_price" => ["numeric"],
        ]);

        if ($validator->fails()) {
            return $this->handleResponse(
                false,
                "",
                [$validator->errors()->first()],
                [],
                []
            );
        }

        $product = Product::find($request->product_id);
        $sellPrice = $request->sell_price;

        if ($product) {
            $user = $request->user();
            $product_if_in_user_cart = $user->cart()->where("product_id", $request->product_id)->first();
            if ($product_if_in_user_cart) {
                $product_if_in_user_cart->sell_price = $sellPrice;
                $product_if_in_user_cart->save();

                    return $this->handleResponse(
                        true,
                        "تم تحديث سعر البيع بنجاح",
                        [],
                        [],
                        []
                    );
            } else {
                return $this->handleResponse(
                    false,
                    "",
                    ["هذا المنتج غير متاح لديك في العربة"],
                    [],
                    []
                );
            }
        } else {
            return $this->handleResponse(
                false,
                "",
                ["هذا المنتج غير متاح"],
                [],
                []
            );
        }
    }

    public function getCartDetails(Request $request) {
        $user = $request->user();
        $cart = $user->cart()->get();
        $sub_total = 0;
        $profit = 0;

        if ($cart->count() > 0)
            foreach ($cart as $item) {
                $item_product = $item->product()->with(["gallery" => function ($q) {
                    $q->take(1);
                }])->first();
                if ($item_product) :
                    if (isset($item->sell_price)) {
                        $itemTotal = $item->sell_price * $item->quantity;
                        $sub_total += $itemTotal;
                    } else {
                        $item->profit = (((int) $item->sell_price ?? (int) $item_product->price) * (int) $item->quantity) - (((int) $item->wholesale_price) * (int) $item->quantity);
                        $item->total = ((int) $item->sell_price ?? (int) $item_product->price) * (int) $item->quantity;
                        $sub_total += $item->total;
                        $profit += $item->profit;
                    }
                    endif;
                $item->dose_product_missing = $item_product ? false : true;
                $item->product = $item_product ?? "This product is missing may deleted!";
            }

        $cartDetails = [
            "products" => $cart,
            "sub_total" => $sub_total,
            "profit" => $profit
        ];

        return $this->handleResponse(
            true,
            "عملية ناجحة",
            [],
            [$cartDetails],
            []
        );
    }
}
