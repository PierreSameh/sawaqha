<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrdersController;
use App\Http\Middleware\GuestAdminMiddleware;

Route::prefix('admin')->group(function () {
    Route::post("login", [AuthController::class, "login"])->middleware([GuestAdminMiddleware::class])->name("admin.login.post");
    Route::get("login", [AuthController::class, "index"])->middleware([GuestAdminMiddleware::class]);

    Route::middleware(['auth:admin'])->group(function () {
        // Dashboard
        Route::get("/dashboard", [DashboardController::class, "index"])->name("admin.dashboard");

        // Categories
        Route::prefix('categories')->group(function () {
            Route::get("/", [CategoryController::class, "index"])->name("admin.categories.show");
            Route::get("/get", [CategoryController::class, "get"])->name("admin.categories.get");
            Route::get("/create", [CategoryController::class, "add"])->name("admin.categories.add");
            Route::post("/create", [CategoryController::class, "create"])->name("admin.categories.create");
            Route::get("/edit/{id}", [CategoryController::class, "edit"])->name("admin.categories.edit");
            Route::post("/update", [CategoryController::class, "update"])->name("admin.categories.update");
            Route::get("/delete/{id}", [CategoryController::class, "deleteIndex"])->name("admin.categories.delete.confirm");
            Route::post("/delete", [CategoryController::class, "delete"])->name("admin.categories.delete");
        });

        // Products
        Route::prefix('products')->group(function () {
            Route::get("/", [ProductsController::class, "index"])->name("admin.products.show");
            Route::get("/get", [ProductsController::class, "get"])->name("admin.products.get");
            Route::get("/create", [ProductsController::class, "add"])->name("admin.products.add");
            Route::post("/create", [ProductsController::class, "create"])->name("admin.products.create");
            Route::get("/edit/{id}", [ProductsController::class, "edit"])->name("admin.products.edit");
            Route::post("/update", [ProductsController::class, "update"])->name("admin.products.update");
            Route::get("/delete/{id}", [ProductsController::class, "deleteIndex"])->name("admin.products.delete.confirm");
            Route::post("/delete", [ProductsController::class, "delete"])->name("admin.products.delete");
        });
        // Orders
        Route::prefix('orders')->group(function () {
            Route::get("/order/{id}", [OrdersController::class, "order"])->name("admin.orders.order.details");
            Route::get("/all", [OrdersController::class, "index"])->name("admin.orders.show.all");
            Route::get("/review", [OrdersController::class, "indexReview"])->name("admin.orders.show.review");
            Route::get("/confirmed", [OrdersController::class, "indexConfirmed"])->name("admin.orders.show.confirmed");
            Route::get("/delivary", [OrdersController::class, "indexDelivary"])->name("admin.orders.show.delivary");
            Route::get("/completed", [OrdersController::class, "indexCompleted"])->name("admin.orders.show.completed");
            Route::get("/canceled", [OrdersController::class, "indexCanceled"])->name("admin.orders.show.canceled");

            Route::get("/order/approve/{id}", [OrdersController::class, "approveIndex"])->name("admin.orders.approve");
            Route::post("/order/approve/confirm/{id}", [OrdersController::class, "approve"])->name("admin.orders.approve.post");
            Route::get("/order/cancel/{id}", [OrdersController::class, "cancelIndex"])->name("admin.orders.cancel");
            Route::post("/order/cancel/confirm/{id}", [OrdersController::class, "cancel"])->name("admin.orders.cancel.post");
            Route::get("/order/success/{id}", [OrdersController::class, "successIndex"])->name("admin.orders.success");
        });
    });
});
