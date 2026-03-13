@extends('layouts.admin')

@section('title', $category->exists ? 'Sửa danh mục' : 'Thêm danh mục')

@section('content')
<div class="panel p-4">
    <h1 class="h3 mb-3">{{ $category->exists ? 'Sửa danh mục' : 'Thêm danh mục' }}</h1>
    <form method="post" enctype="multipart/form-data" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="row g-3">
        @csrf
        @if($category->exists)
            @method('PUT')
        @endif

        <div class="col-md-6">
            <label class="form-label">Tên danh mục</label>
            <input class="form-control" name="name" placeholder="Nhập tên danh mục" value="{{ old('name', $category->name) }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Danh mục cha</label>
            <select class="form-select" name="parent_id">
                <option value="">Không có danh mục cha</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}" @selected(old('parent_id', $category->parent_id) == $parent->id)>{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Độ tuổi</label>
            <input class="form-control" name="age_group" placeholder="Ví dụ: 0-6 tháng" value="{{ old('age_group', $category->age_group) }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Thứ tự hiển thị</label>
            <input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order ?? 0) }}">
        </div>

        <div class="col-md-6">
            <label class="form-label">Ảnh danh mục</label>
            <input class="form-control" type="file" name="image_file" accept="image/*">
            @if($category->image)
                <img src="{{ $category->image }}" alt="Ảnh danh mục" class="mt-2 rounded" style="width:88px;height:88px;object-fit:cover;">
            @endif
        </div>

        <div class="col-md-6">
            <label class="form-label">Icon danh mục</label>
            <input class="form-control" type="file" name="icon_file" accept="image/*">
            @if($category->icon)
                <img src="{{ $category->icon }}" alt="Icon danh mục" class="mt-2 rounded" style="width:52px;height:52px;object-fit:cover;background:#f3f4f6;">
            @endif
        </div>

        <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea class="form-control" name="description" rows="3" placeholder="Mô tả ngắn cho danh mục">{{ old('description', $category->description) }}</textarea>
        </div>

        <div class="col-md-3 form-check pt-2 ms-2">
            <input class="form-check-input" type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $category->is_featured))>
            <label class="form-check-label">Danh mục nổi bật</label>
        </div>

        <div class="col-md-3 form-check pt-2 ms-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->exists ? $category->is_active : true))>
            <label class="form-check-label">Hiển thị</label>
        </div>

        <div class="col-12">
            <button class="btn btn-primary">{{ $category->exists ? 'Cập nhật danh mục' : 'Lưu danh mục' }}</button>
        </div>
    </form>
</div>
@endsection
