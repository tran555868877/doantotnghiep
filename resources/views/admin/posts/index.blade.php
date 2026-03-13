@extends('layouts.admin')

@section('title', 'Bài viết')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Bài viết</h1>
    <a class="btn btn-primary" href="{{ route('admin.posts.create') }}">Thêm mới</a>
</div>

<div class="panel p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Trạng thái</th>
                <th class="text-end">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->status === 'published' ? 'Đã xuất bản' : 'Bản nháp' }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.posts.edit', $post) }}">Sửa</a>
                        <form class="d-inline" method="post" action="{{ route('admin.posts.destroy', $post) }}">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $posts->links() }}
</div>
@endsection
