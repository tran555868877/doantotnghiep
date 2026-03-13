@extends('layouts.admin')

@section('title', $product->exists ? 'Sửa sản phẩm' : 'Thêm sản phẩm')

@section('content')
<div class="panel p-4">
    <h1 class="h3 mb-3">{{ $product->exists ? 'Sửa sản phẩm' : 'Thêm sản phẩm' }}</h1>
    <form method="post" enctype="multipart/form-data" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" class="row g-3">
        @csrf
        @if($product->exists)
            @method('PUT')
        @endif

        <div class="col-md-6">
            <label class="form-label">Tên sản phẩm</label>
            <input class="form-control" name="name" placeholder="Tên sản phẩm" value="{{ old('name', $product->name) }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Danh mục</label>
            <select class="form-select" name="category_id" required>
                @foreach($categoryTree as $parent)
                    <option value="{{ $parent->id }}" @selected(old('category_id', $product->category_id) == $parent->id)>
                        {{ $parent->name }}
                    </option>
                    @foreach($parent->children as $child)
                        <option value="{{ $child->id }}" @selected(old('category_id', $product->category_id) == $child->id)>
                            ├── {{ $child->name }}
                        </option>
                    @endforeach
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">SKU</label>
            <input class="form-control" name="sku" placeholder="SKU" value="{{ old('sku', $product->sku) }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Thương hiệu</label>
            <input class="form-control" name="brand" placeholder="Thương hiệu" value="{{ old('brand', $product->brand) }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Xuất xứ</label>
            <input class="form-control" name="origin_country" placeholder="Xuất xứ" value="{{ old('origin_country', $product->origin_country) }}">
        </div>

        <div class="col-md-3">
            <label class="form-label">Độ tuổi</label>
            <input class="form-control" name="age_group" placeholder="Ví dụ: 0-6 tháng" value="{{ old('age_group', $product->age_group) }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Đơn vị</label>
            <input class="form-control" name="unit" placeholder="sp, hộp..." value="{{ old('unit', $product->unit ?: 'sp') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Giá</label>
            <input class="form-control" name="price" placeholder="Giá bán" value="{{ old('price', $product->price) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Giá khuyến mãi</label>
            <input class="form-control" name="sale_price" placeholder="Giá KM" value="{{ old('sale_price', $product->sale_price) }}">
        </div>

        <div class="col-md-4">
            <label class="form-label">Tồn kho</label>
            <input class="form-control" name="stock" placeholder="Tồn kho" value="{{ old('stock', $product->stock ?? 0) }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Đánh giá</label>
            <input class="form-control" name="rating" placeholder="Điểm đánh giá" value="{{ old('rating', $product->rating ?? 5) }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Ảnh đại diện</label>
            <input class="form-control" type="file" name="thumbnail_file" accept="image/*">
            @if($product->thumbnail)
                <img src="{{ $product->thumbnail }}" alt="Ảnh đại diện" class="mt-2 rounded" style="width:88px;height:88px;object-fit:cover;">
            @endif
        </div>

        <div class="col-12">
            <label class="form-label">Ảnh thư viện (chọn nhiều ảnh)</label>
            <input class="form-control" type="file" name="gallery_files[]" accept="image/*" multiple>
            @if(is_array($product->gallery) && count($product->gallery))
                <div class="d-flex flex-wrap gap-2 mt-2">
                    @foreach($product->gallery as $image)
                        <img src="{{ $image }}" alt="Ảnh gallery" style="width:72px;height:72px;object-fit:cover;border-radius:10px;background:#f3f4f6;">
                    @endforeach
                </div>
            @endif
        </div>

        <div class="col-12">
            <label class="form-label">Mô tả ngắn</label>
            <textarea class="form-control" name="short_description" rows="2" placeholder="Mô tả ngắn">{{ old('short_description', $product->short_description) }}</textarea>
        </div>
        <div class="col-12">
            <label class="form-label">Mô tả chi tiết</label>
            <textarea class="form-control" name="description" rows="5" placeholder="Mô tả chi tiết">{{ old('description', $product->description) }}</textarea>
        </div>
        <div class="col-12">
            <label class="form-label">Thông số kỹ thuật (mỗi dòng: Tên: Giá trị)</label>
            <textarea class="form-control" name="attributes" rows="4" placeholder="Ví dụ: Trọng lượng: 500g">{{ old('attributes', is_array($product->attributes) ? collect($product->attributes)->map(fn($v, $k) => $k.': '.$v)->implode(PHP_EOL) : '') }}</textarea>
        </div>

        <div class="col-md-3 form-check pt-2 ms-2">
            <input class="form-check-input" type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))>
            <label class="form-check-label">Sản phẩm nổi bật</label>
        </div>
        <div class="col-md-3 form-check pt-2 ms-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->exists ? $product->is_active : true))>
            <label class="form-check-label">Hiển thị</label>
        </div>

        <div class="col-12">
            <button class="btn btn-primary">{{ $product->exists ? 'Cập nhật sản phẩm' : 'Lưu sản phẩm' }}</button>
        </div>
    </form>
</div>
@endsection
