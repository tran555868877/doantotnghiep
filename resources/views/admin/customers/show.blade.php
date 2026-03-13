@extends('layouts.admin')

@section('title', 'Chi tiết khách hàng')

@section('content')
@php
    $retention = (float) ($customer->customerScore->retention_probability ?? 0);
    $interestLevel = $retention >= 70 ? 'Nhóm A - Cao' : ($retention >= 40 ? 'Nhóm B - Trung bình' : 'Nhóm C - Thấp');
@endphp
<div class="row g-4">
    <div class="col-lg-4">
        <div class="panel p-4">
            <h1 class="h4">{{ $customer->name }}</h1>
            <div>{{ $customer->email }}</div>
            <div>{{ $customer->phone }}</div>
            <hr>
            <div><strong>Segment:</strong> {{ strtoupper($customer->customerScore->segment ?? 'cold') }}</div>
            <div><strong>Retention:</strong> {{ $customer->customerScore->retention_probability ?? 0 }}%</div>
            <div><strong>Mức quan tâm:</strong> {{ $interestLevel }}</div>
            <div><strong>Danh mục yêu thích:</strong> {{ $customer->customerScore->favoriteCategory->name ?? '-' }}</div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="panel p-4 mb-4">
            <h2 class="h5">Lịch sử đơn hàng</h2>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Mã đơn</th><th>Tổng tiền</th><th>Trạng thái</th></tr></thead>
                    <tbody>
                    @foreach($customer->orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ number_format($order->grand_total, 0, ',', '.') }}đ</td>
                            <td>{{ $order->status }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel p-4">
            <h2 class="h5">Hành vi gần đây</h2>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead><tr><th>Sự kiện</th><th>Từ khóa</th><th>Thời gian</th></tr></thead>
                    <tbody>
                    @foreach($customer->behaviorEvents->sortByDesc('occurred_at')->take(20) as $event)
                        <tr>
                            <td>{{ $event->event_type }}</td>
                            <td>{{ $event->search_keyword ?: '-' }}</td>
                            <td>{{ optional($event->occurred_at)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
