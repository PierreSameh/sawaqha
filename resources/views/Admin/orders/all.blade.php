@extends('Admin.layouts.main')

@section("title", "Orders - All")

@php
    $orders = App\Models\Order::latest()->with("user")->paginate(15);
    $ordersCount = count(App\Models\Order::all());
@endphp

@section("content")
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">All Orders</h1>
    <h3>Orders: {{$ordersCount}}</h3>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive p-2">
            <table class="table table-bordered" width="100%" cellspacing="0" style="white-space: nowrap;">
                <thead>
                    <tr>
                        <th>Ordered by</th>
                        <th>User Account Type</th>
                        <th>Recipient Name</th>
                        <th>Recipient Phone</th>
                        <th>Recipient Address</th>
                        <th>Sub Total</th>
                        <th>Sell Price</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->user ? $order->user->name : "Missing" }}</td>
                            <td>{{ $order->user_type }}</td>
                            <td>{{ $order->recipient_name }}</td>
                            <td>{{ $order->recipient_phone }}</td>
                            <td>{{ $order->recipient_address }}</td>
                            <td>{{ $order->sub_total }}</td>
                            <td>{{ $order->total_sell_price }}</td>
                            <td>{{ $order->status == 1 ? "Under Review" : ($order->status == 2 ? "Confirmed" : ($order->status == 3 ? "On Shipping" : ($order->status == 4 ? "Completed" : ($order->status == 0 ? "Canceled" : "Undifiened")))) }}</td>
                            <td>{{ $order->created_at }}</td>
                            <td>
                                <a href="{{ route("admin.orders.order.details", ["id" => $order->id]) }}" class="btn btn-success">Show</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($orders->hasPages())
        <div class="d-flex laravel_pagination mt-5">
            {!! $orders->links() !!}
        </div>
        @endif

    </div>
</div>

@endSection


@section("scripts")
<script src="{{ asset('/admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('/admin/js/demo/datatables-demo.js') }}"></script>
@endSection
