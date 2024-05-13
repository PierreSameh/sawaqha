@extends('Admin.layouts.main')

@section("title", "Products")

@php
    $products = App\Models\Product::all();
@endphp

@section("content")
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Products</h1>
    <a href="{{ route("admin.products.add") }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-plus fa-sm text-white-50"></i> Create Product</a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $prod)
                        <tr>
                            <td>{{ $prod->name }}</td>
                            <td>{{ substr($prod->description, 0, 100) }}</td>
                            <td>
                                <a href="{{ route("admin.products.edit", ["id" => $prod->id]) }}" class="btn btn-success">Edit</a>
                                <a href="{{ route("admin.products.delete.confirm", ["id" => $prod->id]) }}" class="btn btn-danger">Delete</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endSection


@section("scripts")
<script src="{{ asset('/admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('/admin/js/demo/datatables-demo.js') }}"></script>
@endSection
