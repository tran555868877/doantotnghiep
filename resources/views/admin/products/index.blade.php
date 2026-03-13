@extends('layouts.admin')

@section('title', 'Sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Sản phẩm</h1>
    <a class="btn btn-primary" href="{{ route('admin.products.create') }}">Thêm mới</a>
</div>

<div class="panel p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>Danh mục</th>
                <th>Giá bán</th>
                <th>Tồn kho</th>
                <th class="text-end">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($products as $product)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}" style="width:46px;height:46px;object-fit:cover;border-radius:10px;background:#f3f4f6;">
                            <span>{{ $product->name }}</span>
                        </div>
                    </td>
                    <td>{{ $product->category->name }}</td>
                    <td>{{ number_format($product->final_price, 0, ',', '.') }}đ</td>
                    <td>{{ $product->stock }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.products.edit', $product) }}">Sửa</a>
                        <form class="d-inline" method="post" action="{{ route('admin.products.destroy', $product) }}">
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
    {{ $products->links() }}
</div>
@endsection
