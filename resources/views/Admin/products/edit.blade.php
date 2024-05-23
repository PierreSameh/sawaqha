@extends('Admin.layouts.main')

@section("title", "Products - Edit")
@section("loading_txt", "Update")

@section("content")
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Update Product</h1>
    <a href="{{ route("admin.products.show") }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
</div>
@php
    $categories = App\Models\Category::latest()->get();
@endphp
<div class="card p-3 mb-3" id="products_wrapper">
    <div class="d-flex justify-content-between" style="gap: 16px">
        <div class="w-50">
            <div class="form-group w-100">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name"  placeholder="Product Name" v-model="name">
            </div>
            <div class="form-group w-100">
                <label for="price" class="form-label">Sell Price</label>
                <input type="number" class="form-control" id="price"  placeholder="Sell Price" v-model="price">
            </div>
            <div class="form-group w-100">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity"  placeholder="Quantity" v-model="quantity">
            </div>
            <div class="form-group w-100">
                <label for="wholesale_price" class="form-label">Wholesale Price</label>
                <input type="number" class="form-control" id="wholesale_price"  placeholder="Wholesale Price" v-model="wholesale_price">
            </div>
            <div class="form-group w-100">
                <label for="least_quantity_wholesale" class="form-label">Least quantity wholesale</label>
                <input type="number" class="form-control" id="least_quantity_wholesale"  placeholder="Least quantity wholesale" v-model="least_quantity_wholesale">
            </div>
            <div class="form-group w-100">
                <label for="categories" class="form-label">Category</label>
                <select name="categories" id="categories" class="form-control" v-model="category_id">
                    <option value="" disabled>Select ---</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">@{{ cat.name }}</option>
                </select>
            </div>
        </div>
        <div class="form-group w-50">
            <label for="Description" class="form-label">Description</label>
            <textarea rows="18" class="form-control" id="Description"  placeholder="Description" style="resize: none" v-model="description">
            </textarea>
        </div>
    </div>
    <div class="w-100 form-group">
        <label for="gallary" class="form-control"
        style="display: flex; flex-direction: column; justify-content: center; align-items: center; height: 140px; font-size: 22px;">Upload
        Product Image*
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-photo-plus" width="55"
            height="55" viewBox="0 0 24 24" stroke-width="2" stroke="#2c3e50" fill="none" stroke-linecap="round"
            stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M15 8h.01"></path>
            <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5"></path>
            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l4 4"></path>
            <path d="M14 14l1 -1c.67 -.644 1.45 -.824 2.182 -.54"></path>
            <path d="M16 19h6"></path>
            <path d="M19 16v6"></path>
        </svg>
    </label>
        <input type="file" id="gallary" multiple="" class="form-control" @change="handleChangeImages" style="display: none;">
    </div>
    <div id="preview-gallery" class="mt-3">
        <div class="row">
           <div v-for="(img, index) in gallery" :key="index"
              class="col-lg-3 col-md-6 mb-4">
              <button
                 style="background: transparent; border: medium; border-radius: 50%; float: right;" @click="handleDeleteImageFromGallery(index)">
                 <svg
                    xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="#043343" fill="none" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M18 6l-12 12"></path>
                    <path d="M6 6l12 12"></path>
                 </svg>
              </button>
              <img :src="img.path"
                 style="width: 100%; height: 250px; object-fit: cover;" alt="gallery">
           </div>
           <div v-for="(img, index) in images_path" :key="index"
              class="col-lg-3 col-md-6 mb-4">
              <button
                 style="background: transparent; border: medium; border-radius: 50%; float: right;" @click="handleDeleteImage(index)">
                 <svg
                    xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="#043343" fill="none" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M18 6l-12 12"></path>
                    <path d="M6 6l12 12"></path>
                 </svg>
              </button>
              <img :src="img"
                 style="width: 100%; height: 250px; object-fit: cover;" alt="gallery">
           </div>
        </div>
     </div>

    <div class="form-group">
        <button class="btn btn-success w-25" @click="update" style="display: block;margin: auto">Update</button>
    </div>
</div>

@endSection

@section("scripts")
<script>
const { createApp, ref } = Vue

createApp({
    data() {
        return {
            id: '{{ $product->id }}',
            category_id: '{{ $product->category_id }}',
            name: '{{ $product->name }}',
            description: '{{ $product->description }}',
            price: '{{ $product->price }}',
            quantity: '{{ $product->quantity }}',
            wholesale_price: '{{ $product->wholesale_price }}',
            least_quantity_wholesale: '{{ $product->least_quantity_wholesale }}',
            gallery: @json($product->gallery),
            categories: @json($categories),
            deletedGallery: [],
            images_path: [],
            images: []
        }
    },
    methods: {
        handleChangeImages(event) {
            let files = Array.from(event.target.files)
            files.map(file => {
                this.images.push(file)
                this.images_path.push(URL.createObjectURL(file))
            })
        },
        handleDeleteImage(index) {
            let arr = this.images
            arr.splice(index, 1)
            this.images = arr
            let arr_paths  = this.images_path
            arr_paths.splice(index, 1)
            this.images_path = arr_paths
        },
        handleDeleteImageFromGallery(index) {
            let dArr = this.deletedGallery
            dArr.push(this.gallery[index])
            this.deletedGallery = dArr
            let arr = this.gallery
            arr.splice(index, 1)
            this.gallery = arr
        },
        async update() {
            $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`{{ route("admin.products.update") }}`, {
                    id: this.id,
                    name: this.name,
                    description: this.description,
                    price: this.price,
                    quantity: this.quantity,
                    wholesale_price: this.wholesale_price,
                    least_quantity_wholesale: this.least_quantity_wholesale,
                    images: this.images,
                    deleted_gallery: this.deletedGallery,
                    category_id: this.category_id,
                },
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                },
                );
                if (response.data.status === true) {
                    document.getElementById('errors').innerHTML = ''
                    let error = document.createElement('div')
                    error.classList = 'success'
                    error.innerHTML = response.data.message
                    document.getElementById('errors').append(error)
                    $('#errors').fadeIn('slow')
                    setTimeout(() => {
                        $('.loader').fadeOut()
                        $('#errors').fadeOut('slow')
                        window.location.href = '{{ route("admin.products.show") }}'
                    }, 1300);
                } else {
                    $('.loader').fadeOut()
                    document.getElementById('errors').innerHTML = ''
                    $.each(response.data.errors, function (key, value) {
                        let error = document.createElement('div')
                        error.classList = 'error'
                        error.innerHTML = value
                        document.getElementById('errors').append(error)
                    });
                    $('#errors').fadeIn('slow')
                    setTimeout(() => {
                        $('#errors').fadeOut('slow')
                    }, 5000);
                }

            } catch (error) {
                document.getElementById('errors').innerHTML = ''
                let err = document.createElement('div')
                err.classList = 'error'
                err.innerHTML = 'server error try again later'
                document.getElementById('errors').append(err)
                $('#errors').fadeIn('slow')
                $('.loader').fadeOut()

                setTimeout(() => {
                    $('#errors').fadeOut('slow')
                }, 3500);

                console.error(error);
            }
        }
    },
}).mount('#products_wrapper')
</script>
@endSection
