@extends('Admin.layouts.main')

@section("title", "Categories")

@php
    $categories = App\Models\Category::all();
@endphp

@section("content")
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">الفئات</h1>
    <a href="{{ route("admin.categories.add") }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
        <i class="fas fa-plus fa-sm text-white-50"></i> إنشاء فئة
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <!-- Filter Dropdown -->
        <div class="mb-3">
            <label for="categoryFilter">تصفية حسب النوع:</label>
            <select id="categoryFilter" class="form-control" style="width: auto; display: inline-block;">
                <option value="all">جميع الفئات</option>
                <option value="main">الفئات الرئيسية</option>
                <option value="sub">الفئات الفرعية</option>
            </select>
        </div>

        <!-- Table -->
        <div class="table-responsive" style="width: auto">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>الاسم</th>
                        <th>الوصف</th>
                        <th>الخيارات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $cat)
                        <tr class="category-row" data-category-type="{{ $cat->is_main_category ? 'main' : 'sub' }}">
                            <td>{{ $cat->name }}</td>
                            <td>{{ substr($cat->description, 0, 100) }}</td>
                            <td>
                                <a href="{{ route("admin.categories.edit", ["id" => $cat->id]) }}" class="btn btn-success">تعديل</a>
                                <a href="{{ route("admin.categories.delete.confirm", ["id" => $cat->id]) }}" class="btn btn-danger">إزالة</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section("scripts")
<script src="{{ asset('/admin/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/admin/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('/admin/js/demo/datatables-demo.js') }}"></script>

<!-- Filter Script -->
<script>
    document.getElementById('categoryFilter').addEventListener('change', function() {
        const filterValue = this.value;
        const rows = document.querySelectorAll('.category-row');

        rows.forEach(row => {
            const isMainCategory = row.getAttribute('data-category-type') === 'main';

            if (filterValue === 'all' || 
               (filterValue === 'main' && isMainCategory) || 
               (filterValue === 'sub' && !isMainCategory)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection
