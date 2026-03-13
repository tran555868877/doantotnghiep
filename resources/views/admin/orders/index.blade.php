@extends('layouts.admin')

@section('title', 'Đơn hàng')

@section('content')
<h1 class="h3 mb-3">Đơn hàng</h1>
<div class="panel p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th class="text-end">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ number_format($order->grand_total, 0, ',', '.') }}đ</td>
                    <td>{{ $order->status }}</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.orders.show', $order) }}">Xem</a>
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders.edit', $order) }}">Sửa</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
</div>
@endsection
