@extends('Admin.layouts.main')

@section("title", "Request #" . $request->id . " Details")

@section("content")
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Request #{{$request->id}} Details</h1>
    <a href="{{ route("admin.requests.show.all") }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
</div>
<div class="card p-3 mb-3">
    <h2>Requestd by:</h2>
    <div class="user_details" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px">
        <div class="form-group">
            <label>User Name</label>
            <span class="form-control">{{ $request->user->name }}</span>
        </div>
        <div class="form-group">
            <label>User Email</label>
            <span class="form-control">{{ $request->user->email }}</span>
        </div>
        <div class="form-group">
            <label>User Phone</label>
            <span class="form-control">{{ $request->user->phone }}</span>
        </div>
        <div class="form-group">
            <label>User Current Balance</label>
            <span class="form-control">{{ $request->user->balance}}</span>
        </div>
    </div>
    <hr>
    <h2>Request Information:</h2>
    <div class="user_details" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px">
        <div class="form-group">
            <label>Status</label>
            <span class="form-control">
                {{ $request->status == 1 ? "Under Review" : ($request->status == 2 ? "Confirmed" : ($request->status == 3 ? "On Shipping" : ($request->status == 4 ? "Completed" : ($request->status == 0 ? "Canceled" : "Undifiened")))) }}
            </span>
        </div>
        <div class="form-group">
            <label>Date</label>
            <span class="form-control">{{ $request->created_at }}</span>
        </div>
        <div class="form-group">
            <label>Amount</label>
            <span class="form-control">{{ $request->amount }}</span>
        </div>
        <div class="form-group">
            <label>Way of Withdraw</label>
            <span class="form-control">{{ $request->way_of_getting_money }}</span>
        </div>
        <div class="form-group">
            <label>Wallet or Card number</label>
            <span class="form-control">{{ $request->wallet_or_card_number }}</span>
        </div>
    </div>
    <hr>

    <div class="btns d-flex gap-3 justify-content-center">

        @if($request->status !== 2 && $request->status !== 0)
            <a href="{{route('admin.requests.approve', ['id' => $request->id])}}" class="btn btn-success w-25 m-2">
                {{ $request->status === 1 ? "Confirm!" : '' }}
            </a>
        @endif

        @if($request->status !== 2 && $request->status !== 0)
            <a href="{{route('admin.requests.cancel', ['id' => $request->id])}}"class="btn btn-danger w-25 m-2">Cancel</a>
        @endif
    </div>

</div>

@endSection
