@extends('Admin.layouts.main')

@section("title", "Categories - Edit")
@section("loading_txt", "Update")

@section("content")
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">تعديل الفئة</h1>
    <a href="{{ route("admin.categories.show") }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
            class="fas fa-arrow-left fa-sm text-white-50"></i> العودة إلى الخلف</a>
</div>

<div class="card p-3 mb-3" id="categories_wrapper">
    <div class="d-flex justify-content-between" style="gap: 16px">
        <div class="w-100">
            <div class="form-group w-100">
                <label for="name" class="form-label">الاسم</label>
                <input type="text" class="form-control" id="name"  placeholder="اسم الفئة" v-model="name">
            </div>
            <div class="form-group">
                <label for="Description" class="form-label">الوصف</label>
                <textarea rows="4" class="form-control" id="Description"  placeholder="وصف الفئة" style="resize: none" v-model="description">
                </textarea>
            </div>
            <div class="d-flex justify-content-between" style="gap: 16px">
                <div class="form-group w-50">
                    <label for="name" class="form-label">نوع الفئة</label>
                    <select name="isMian" id="isMain" class="form-control" v-model="category_type">
                        <option value="1">الفئة الرئيسة</option>
                        <option value="2">الفئة الفرعية</option>
                    </select>
                </div>
                @php
                    $categories = App\Models\Category::where("isMainCat", true)->get();
                @endphp
                <div class="form-group w-50" v-if="category_type == 2">
                    <label for="name" class="form-label">اختر الفئة الرئيسة</label>
                    <select name="isMian" id="isMain" class="form-control" v-model="parent_category_id">
                        @foreach ($categories as $item)
                            <option value="{{$item->id}}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        </div>
        <div class="form-group pt-4 pb-4" style="width: max-content; height: 300px;min-width: 250px">
            <label for="thumbnail" class="w-100 h-100">
                <svg v-if="!thumbnail && !thumbnail_path" xmlns="http://www.w3.org/2000/svg" className="icon icon-tabler icon-tabler-photo-up" width="24" height="24" viewBox="0 0 24 24" strokeWidth="1.5" style="width: 100%; height: 100%; object-fit: cover; padding: 10px; border: 1px solid; border-radius: 1rem" stroke="#043343" fill="none" strokeLinecap="round" strokeLinejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M15 8h.01" />
                    <path d="M12.5 21h-6.5a3 3 0 0 1 -3 -3v-12a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v6.5" />
                    <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l3.5 3.5" />
                    <path d="M14 14l1 -1c.679 -.653 1.473 -.829 2.214 -.526" />
                    <path d="M19 22v-6" />
                    <path d="M22 19l-3 -3l-3 3" />
                </svg>
                <img v-if="thumbnail_path" :src="thumbnail_path" style="width: 100%; height: 100%; object-fit: cover; padding: 10px; border: 1px solid; border-radius: 1rem" />
            </label>
        <input type="file" class="form-control d-none" id="thumbnail"  placeholder="صورة الفئة" @change="handleChangeThumbnail">
        </div>
    </div>
    <div class="form-group">
        <button class="btn btn-success w-25" @click="update">تعديل</button>
    </div>
</div>

@endSection

@section("scripts")
<script>
const { createApp, ref } = Vue

createApp({
    data() {
        return {
            id: '{{ $category->id }}',
            name: '{{ $category->name }}',
            description: '{{ $category->description }}',
            category_type: "{{ $category->isMainCat ? 1 : 2 }}",
            parent_category_id: "{{ $category->category_id }}",
            thumbnail: null,
            thumbnail_path: '{{ $category->thumbnail_path }}',
        }
    },
    methods: {
        handleChangeThumbnail(event) {
            this.thumbnail = event.target.files[0]
            this.thumbnail_path = URL.createObjectURL(event.target.files[0])
        },
        async update() {
            $('.loader').fadeIn().css('display', 'flex')
            try {
                const response = await axios.post(`{{ route("admin.categories.update") }}`, {
                    id: this.id,
                    name: this.name,
                    description: this.description,
                    category_type: this.category_type,
                    parent_category_id: this.parent_category_id,
                    thumbnail: this.thumbnail,
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
                        window.location.href = '{{ route("admin.categories.show") }}'
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
    created() {
        console.log(this.parent_category_id);
    }
}).mount('#categories_wrapper')
</script>
@endSection
