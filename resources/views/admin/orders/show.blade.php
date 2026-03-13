@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng')

@section('content')
<div class="panel p-4">
    <h1 class="h3 mb-2">{{ $order->order_number }}</h1>
    <p class="mb-1">{{ $order->customer_name }} | {{ $order->customer_phone }} | {{ $order->customer_email }}</p>
    <p class="mb-3"><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</p>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr><th>Sản phẩm</th><th>Số lượng</th><th>Đơn giá</th><th>Thành tiền</th></tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
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
