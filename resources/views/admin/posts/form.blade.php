@extends('layouts.admin')

@section('title', $post->exists ? 'Sửa bài viết' : 'Thêm bài viết')

@section('content')
<div class="panel p-4">
    <h1 class="h3 mb-3">{{ $post->exists ? 'Sửa bài viết' : 'Thêm bài viết' }}</h1>
    <form method="post" enctype="multipart/form-data" action="{{ $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store') }}" class="row g-3">
        @csrf
        @if($post->exists)
            @method('PUT')
        @endif

        <div class="col-12">
            <label class="form-label">Tiêu đề</label>
            <input class="form-control" name="title" placeholder="Tiêu đề bài viết" value="{{ old('title', $post->title) }}" required>
        </div>

        <div class="col-12">
            <label class="form-label">Ảnh đại diện</label>
            <input class="form-control" type="file" name="thumbnail_file" accept="image/*">
            @if($post->thumbnail)
                <img src="{{ $post->thumbnail }}" alt="Ảnh bài viết" class="mt-2 rounded" style="width:108px;height:72px;object-fit:cover;">
            @endif
        </div>

        <div class="col-12">
            <label class="form-label">Tóm tắt</label>
            <textarea class="form-control" name="excerpt" rows="2" placeholder="Tóm tắt ngắn bài viết">{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>

        <div class="col-md-4">
            <label class="form-label">Trạng thái</label>
            <select class="form-select" name="status" required>
                <option value="published" @selected(old('status', $post->status ?: 'published') === 'published')>Đã xuất bản</option>
                <option value="draft" @selected(old('status', $post->status ?: 'published') === 'draft')>Bản nháp</option>
            </select>
        </div>

        <div class="col-12">
            <label class="form-label">Nội dung</label>
            <textarea class="form-control" name="content" rows="10" placeholder="Nội dung chi tiết bài viết" required>{{ old('content', $post->content) }}</textarea>
        </div>

        <div class="col-12">
            <button class="btn btn-primary">{{ $post->exists ? 'Cập nhật bài viết' : 'Lưu bài viết' }}</button>
        </div>
    </form>
</div>
@endsection
