@extends("Admin.layouts.main")

@section("content")
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
    </div>
    <form method="POST" action="{{route('admin.store.social')}}" style="background-color: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); max-width: 400px; margin: 20px auto;">
        @csrf
        <div class="whatsapp" style="margin-bottom: 15px;">
            <label for="whatsapp" style="display: block; font-weight: bold; margin-bottom: 5px;">Whatsapp suggestion</label>
            <input type="text" name="whatsapp" id="whatsapp" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
        </div>
        <div class="facebook" style="margin-bottom: 15px;">
            <label for="facebook" style="display: block; font-weight: bold; margin-bottom: 5px;">Whatsapp Wholesale</label>
            <input type="text" name="facebook" id="facebook" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
        </div>
        <div class="submit" style="text-align: center;">
            <button type="submit" style="background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Submit</button>
        </div>
    </form>

@endSection
