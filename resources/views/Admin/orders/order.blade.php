@extends('Admin.layouts.main')

@section("title", "Order #" . $order->id . " Details")

@section("content")
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Order #{{$order->id}} Details</h1>
    <a href="{{ route("admin.orders.show.all") }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
</div>
<div class="card p-3 mb-3">
    <!-- Order Details Content -->

    <div class="btns d-flex gap-3 justify-content-center">
        @if($order->status !== 4 && $order->status !== 0)
            <a href="{{route('admin.orders.approve', ['id' => $order->id])}}" class="btn btn-success w-25 m-2">
                {{ $order->status === 1 ? "Confirm!" : '' }}
                {{ $order->status === 2 ? "Start Shipping!" : '' }}
                {{ $order->status === 3 ? "Complete!" : '' }}
            </a>
        @endif
        @if($order->status !== 4 && $order->status !== 0)
            <a href="{{route('admin.orders.cancel', ['id' => $order->id])}}" class="btn btn-danger w-25 m-2">Cancel</a>
        @endif

        <!-- Button to Open Receipt Modal -->
        <button type="button" class="btn btn-info w-25 m-2" data-toggle="modal" data-target="#receiptModal">
            View Receipt
        </button>
    </div>
</div>

<!-- Receipt Modal -->
<div id="receiptContent">
    <div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 600px;">
            <div class="modal-content" style="direction: rtl; text-align: right;">
                <div class="modal-header">
                    <h5 class="modal-title" id="receiptModalLabel">فاتورة الطلب #{{$order->id}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-left: 0;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="receiptContent">
                    <!-- Receipt Content -->
                    <h3>ملخص الطلب</h3>
                    <p><strong>تم الطلب بواسطة:</strong> {{ $order->user ? $order->user->name : "مفقود" }}</p>
                    <p><strong>المستلم:</strong> {{ $order->recipient_name }}</p>
                    <p><strong>الهاتف:</strong> {{ $order->recipient_phone }}</p>
                    <p><strong>العنوان:</strong> {{ $order->recipient_address }}</p>
                    <hr>
                    
                    <h4>المنتجات</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>اسم المنتج</th>
                                <th>الكود</th>
                                <?php
                                $hasColor = $order->products->contains(function ($product) {
                                    return !is_null($product->color);
                                }); 
                                $hasSize = $order->products->contains(function ($product) {
                                    return !is_null($product->size);
                                }); 
                                ?>
                                {!! $hasColor ? '<th>اللون</th>' : '' !!}
                                {!! $hasSize ? '<th>الحجم</th>' : '' !!}
                                <th>السعر</th>
                                <th>الكمية</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->products as $product)
                                <tr>
                                    <td>{{ $product->product->name }}</td>
                                    <td>{{ $product->product->id }}</td>
                                    {!! $product->color ? '<td>' . $product->color . '</td>' : '' !!}
                                    {!! $product->size ? '<td>' . $product->size . '</td>' : '' !!}
                                    <td>{{ $product->price_in_order }}</td>
                                    <td>{{ $product->ordered_quantity }}</td>
                                    <td>{{ $product->price_in_order * $product->ordered_quantity }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <hr>
                    <p><strong>الإجمالي الفرعي:</strong> {{ $order->sub_total }}</p>
                    <p><strong>الشحن:</strong> {{ $order->shipping }}</p>
                    <p><strong>الإجمالي الكلي:</strong> {{ $order->sub_total + $order->shipping }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                    <!-- Print Button -->
                    <button type="button" class="btn btn-primary" onclick="printReceipt()">طباعة الفاتورة</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card p-3 mb-3">
    <h2>Orderd by:</h2>
    <div class="user_details" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px">
        <div class="form-group">
            <label>User Name</label>
            <span class="form-control">{{ $order->user ? $order->user->name : "Missing" }}</span>
        </div>
        <div class="form-group">
            <label>User Email</label>
            <span class="form-control">{{ $order->user ? $order->user->email : "Missing" }}</span>
        </div>
        <div class="form-group">
            <label>User Phone</label>
            <span class="form-control">{{ $order->user ? $order->user->phone : "Missing" }}</span>
        </div>
        <div class="form-group">
            <label>User Type</label>
            <span class="form-control">{{  $order->user ? ($order->user->user_type == 1 ? "Markter" : ( $order->user->user_type == 2 ?  "Trader" : "Undifined")) :  "Missing"}}</span>
        </div>
    </div>
    <hr>
    <h2>Recipient Details:</h2>
    <div class="user_details" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px">
        <div class="form-group">
            <label>Recipient Name</label>
            <span class="form-control">{{ $order->recipient_name }}</span>
        </div>
        <div class="form-group">
            <label>Recipient Phone</label>
            <span class="form-control">{{ $order->recipient_phone }}</span>
        </div>
        <div class="form-group" style="grid-column: span 2">
            <label>Recipient Address</label>
            <span class="form-control">{{ $order->recipient_address }}</span>
        </div>
        <div class="form-group" style="grid-column: span 2">
            <label>Shipping</label>
            <span class="form-control">{{ $order->shipping }}</span>
        </div>
    </div>
    <hr>
    <h2>Order Information:</h2>
    <div class="user_details" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px">
        <div class="form-group">
            <label>Status</label>
            <span class="form-control">
                {{ $order->status == 1 ? "Under Review" : ($order->status == 2 ? "Confirmed" : ($order->status == 3 ? "On Shipping" : ($order->status == 4 ? "Completed" : ($order->status == 0 ? "Canceled" : "Undifiened")))) }}
            </span>
        </div>
        <div class="form-group">
            <label>Date</label>
            <span class="form-control">{{ $order->created_at }}</span>
        </div>
        <div class="form-group">
            <label>Sub Total</label>
            <span class="form-control">{{ $order->sub_total }}</span>
        </div>
    </div>
    <hr>
    <h2>Order Products:</h2>
    <div class="table-responsive p-2">
        <table class="table table-bordered" width="100%" cellspacing="0" style="white-space: nowrap;">
            <thead>
                <tr>
                    <th>Product Id</th>
                    <th>Product Name</th>
                    <th>Product Sold Price</th>
                    <th>Product Sold Quantity</th>
                    <th>Product Category</th>
                    <th>Product Color</th>
                    <th>Product Size</th>
                </tr>
            </thead>
            <tbody>
                    @foreach ($order->products as $product)
                    @if($product->product)
                    <tr>
                        <td>{{ $product->product->id }}</td>
                        <td>{{ $product->product->name }}</td>
                        <td>{{ $product->price_in_order }}</td>
                        <td>{{ $product->ordered_quantity }}</td>
                        <td>{{ $product->product->category->name }}</td>
                        <td>{{ $product->color }}</td>
                        <td>{{ $product->size }}</td>
                    </tr>
                    @else
                    <tr class="text-center text-danger">
                        <td colspan="5">Missing Product may be deleted</td>
                    </tr>
                    @endif
                    @endforeach
            </tbody>
        </table>
    </div>

    <div class="btns d-flex gap-3 justify-content-center">

        @if($order->status !== 4 && $order->status !== 0)
            <a href="{{route('admin.orders.approve', ['id' => $order->id])}}" class="btn btn-success w-25 m-2">
                {{ $order->status === 1 ? "Confirm!" : '' }}
                {{ $order->status === 2 ? "Start Shipping!" : '' }}
                {{ $order->status === 3 ? "Complete!" : '' }}
            </a>
        @endif

        @if($order->status !== 4 && $order->status !== 0)
            <a href="{{route('admin.orders.cancel', ['id' => $order->id])}}"class="btn btn-danger w-25 m-2">Cancel</a>
        @endif
    </div>

</div>

@endSection

@section('scripts')

<script>
    function printReceipt() {
        var printContent = document.getElementById('receiptContent').innerHTML;
        var originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;

        location.reload();
    }
</script>
@endsection
