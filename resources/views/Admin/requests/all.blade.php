@extends('Admin.layouts.main')

@section("title", "Requests - All")

@php
    $requests = App\Models\Money_request::latest()->with("user")->paginate(15);
@endphp

@section("content")
<style>
    .dataTables_wrapper {
        width: auto !important
    }
</style>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">All Requests</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive p-2">
            <table class="table table-bordered requests_table" width="100%" cellspacing="0" style="white-space: nowrap;">
                <thead>
                    <tr>
                        <th>Requested by</th>
                        <th>Amount</th>
                        <th>Withdraw Way</th>
                        <th>Wallet or Card number</th>
                        <th>Status</th>
                        <th class="date_th">Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($requests as $request)
                        <tr>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->amount }}</td>
                            <td>{{ $request->way_of_getting_money }}</td>
                            <td>{{ $request->wallet_or_card_number }}</td>
                            <td>{{ $request->status == 1 ? "Under Review" : ($request->status == 2 ? "Completed" : ($request->status == 3 ? "On Shipping" : ($request->status == 4 ? "Completed" : ($request->status == 0 ? "Canceled" : "Undifiened")))) }}</td>
                            <td>{{ $request->created_at }}</td>
                            <td>
                                <a href="{{ route("admin.requests.request.details", ["id" => $request->id]) }}" class="btn btn-success">Show</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($requests->hasPages())
        <div class="d-flex laravel_pagination mt-5">
            {!! $requests->links() !!}
        </div>
        @endif
    </div>
</div>

@endSection


