@extends('layouts.admin')

@section('title', 'Cập nhật đơn hàng')

@section('content')
<div class="panel p-4 mb-4">
    <h1 class="h3 mb-3">Cập nhật đơn hàng: {{ $order->order_number }}</h1>
    <form method="post" action="{{ route('admin.orders.update', $order) }}" class="row g-3">
        @csrf
        @method('PUT')

        <div class="col-md-6">
            <label class="form-label">Trạng thái đơn hàng</label>
            <select class="form-select" name="status">
                @foreach(['new' => 'Mới', 'confirmed' => 'Đã xác nhận', 'shipping' => 'Đang giao', 'completed' => 'Hoàn tất', 'cancelled' => 'Đã hủy'] as $status => $label)
                    <option value="{{ $status }}" @selected($order->status === $status)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Trạng thái thanh toán</label>
            <select class="form-select" name="payment_status">
                @foreach(['pending' => 'Chờ thanh toán', 'paid' => 'Đã thanh toán', 'failed' => 'Thanh toán lỗi'] as $status => $label)
                    <option value="{{ $status }}" @selected($order->payment_status === $status)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-12">
            <label class="form-label">Ghi chú nội bộ</label>
            <textarea class="form-control" name="notes" rows="4">{{ old('notes', $order->notes) }}</textarea>
        </div>

        <div class="col-12">
            <button class="btn btn-primary">Lưu cập nhật</button>
        </div>
    </form>
</div>

<div class="panel p-4">
    <h2 class="h5 mb-3">Danh sách sản phẩm trong đơn</h2>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Sản phẩm</th>
                <th>SKU</th>
                <th>Số lượng</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->sku ?: '-' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 0, ',', '.') }}đ</td>
                    <td>{{ number_format($item->line_total, 0, ',', '.') }}đ</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
