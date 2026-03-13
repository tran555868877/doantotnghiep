@extends('layouts.admin')

@section('title', 'Dashboard phân tích')

@section('content')
<div class="dashboard-head mb-4">
    <h1 class="h3 mb-1">Dashboard phân tích hành vi khách hàng</h1>
    <p class="text-secondary mb-0">Theo dõi xu hướng truy cập, hiệu suất bán hàng và khả năng quay lại mua hàng.</p>
</div>

@php
    $labels = [
        'products' => 'Sản phẩm',
        'orders' => 'Đơn hàng',
        'customers' => 'Khách hàng',
        'revenue' => 'Doanh thu',
    ];
@endphp

<div class="row g-3 mb-4">
    @foreach($stats as $key => $value)
        <div class="col-md-6 col-xl-3">
            <div class="panel p-4 stat-panel h-100">
                <div class="stat-label">{{ $labels[$key] ?? $key }}</div>
                <div class="stat-value">
                    @if($key === 'revenue')
                        {{ number_format($value, 0, ',', '.') }}đ
                    @else
                        {{ number_format($value, 0, ',', '.') }}
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="panel p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">Xu hướng hành vi 30 ngày</h2>
                <span class="chart-badge">Sự kiện + Đơn + Doanh thu</span>
            </div>
            <div class="chart-box chart-box-lg">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel p-4 h-100">
            <h2 class="h5 mb-3">Phân bổ sự kiện hành vi</h2>
            <div class="chart-box">
                <canvas id="eventDonutChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="panel p-4 h-100">
            <h2 class="h5 mb-3">Phân nhóm khách hàng</h2>
            <div class="chart-box">
                <canvas id="segmentChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel p-4 h-100">
            <h2 class="h5 mb-3">Mức retention</h2>
            <div class="chart-box">
                <canvas id="retentionChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel p-4 h-100">
            <h2 class="h5 mb-3">Top danh mục</h2>
            <div class="chart-box">
                <canvas id="topCategoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="panel p-4 mb-4">
            <h2 class="h5 mb-3">Khách hàng và scoring</h2>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                    <tr><th>Khách hàng</th><th>Segment</th><th>Retention</th><th>Danh mục quan tâm</th></tr>
                    </thead>
                    <tbody>
                    @foreach($customerScores as $score)
                        <tr>
                            <td>{{ $score->user->name }}</td>
                            <td><span class="segment-pill">{{ strtoupper($score->segment) }}</span></td>
                            <td>{{ $score->retention_probability }}%</td>
                            <td>{{ $score->favoriteCategory->name ?? '-' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel p-4">
            <h2 class="h5 mb-3">Đơn hàng mới</h2>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>Trạng thái</th><th>Tổng tiền</th></tr></thead>
                    <tbody>
                    @foreach($latestOrders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->status }}</td>
                            <td>{{ number_format($order->grand_total, 0, ',', '.') }}đ</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="panel p-4 mb-4">
            <h2 class="h5 mb-3">Top sản phẩm bán chạy</h2>
            @foreach($topProducts as $product)
                <div class="rank-row">
                    <span>{{ $product->name }}</span>
                    <strong>{{ $product->sold_count }}</strong>
                </div>
            @endforeach
        </div>
        <div class="panel p-4">
            <h2 class="h5 mb-3">Phân bổ sự kiện (số liệu)</h2>
            @foreach($eventBreakdown as $type => $total)
                <div class="rank-row">
                    <span>{{ $type }}</span>
                    <strong>{{ $total }}</strong>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .dashboard-head h1 {
        font-family: "Quicksand", sans-serif;
        font-weight: 700;
        color: #111;
    }
    .stat-panel {
        border: 1px solid #e5e7eb;
    }
    .stat-label {
        color: #6b7280;
        font-weight: 700;
        margin-bottom: 8px;
    }
    .stat-value {
        font-family: "Quicksand", sans-serif;
        font-size: 30px;
        line-height: 1.05;
        font-weight: 700;
        color: #111;
        word-break: break-word;
    }
    .chart-badge {
        background: #111;
        color: #fff;
        border-radius: 999px;
        padding: 6px 10px;
        font-size: 12px;
        font-weight: 700;
    }
    .chart-box {
        position: relative;
        height: 270px;
        max-height: 270px;
        overflow: hidden;
    }
    .chart-box-lg {
        height: 320px;
        max-height: 320px;
    }
    .segment-pill {
        display: inline-flex;
        border-radius: 999px;
        padding: 4px 10px;
        background: #111;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
    }
    .rank-row {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px solid #ececf0;
    }
    .rank-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }
    .rank-row strong {
        color: #111;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    (function () {
        var data = @json($chartData);
        var palette = {
            dark: '#111111',
            gray: '#4b5563',
            light: '#9ca3af',
            soft: '#e5e7eb'
        };

        Chart.defaults.font.family = 'Source Sans 3, sans-serif';
        Chart.defaults.color = '#374151';

        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [
                    {
                        label: 'Sự kiện',
                        data: data.eventsByDay,
                        borderColor: palette.dark,
                        backgroundColor: 'rgba(17,17,17,0.08)',
                        pointRadius: 2,
                        tension: 0.35,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Đơn hàng',
                        data: data.ordersByDay,
                        borderColor: palette.gray,
                        backgroundColor: 'rgba(75,85,99,0.08)',
                        pointRadius: 2,
                        tension: 0.35,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Doanh thu (x1000đ)',
                        data: data.revenueByDay.map(function (v) { return Math.round(v / 1000); }),
                        borderColor: palette.light,
                        backgroundColor: 'rgba(156,163,175,0.08)',
                        pointRadius: 2,
                        tension: 0.35,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                scales: {
                    y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.06)' } },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: { display: false }
                    }
                }
            }
        });

        new Chart(document.getElementById('eventDonutChart'), {
            type: 'doughnut',
            data: {
                labels: data.eventLabels,
                datasets: [{
                    data: data.eventValues,
                    backgroundColor: ['#111111', '#374151', '#6b7280', '#9ca3af', '#d1d5db'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        new Chart(document.getElementById('segmentChart'), {
            type: 'bar',
            data: {
                labels: data.segmentLabels,
                datasets: [{
                    label: 'Khách hàng',
                    data: data.segmentValues,
                    backgroundColor: '#111111',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        new Chart(document.getElementById('retentionChart'), {
            type: 'pie',
            data: {
                labels: data.retentionLabels,
                datasets: [{
                    data: data.retentionValues,
                    backgroundColor: ['#111111', '#4b5563', '#9ca3af', '#d1d5db'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        new Chart(document.getElementById('topCategoryChart'), {
            type: 'bar',
            data: {
                labels: data.topCategoryLabels,
                datasets: [{
                    label: 'Số sản phẩm',
                    data: data.topCategoryValues,
                    backgroundColor: '#111111',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } }
            }
        });
    })();
</script>
@endpush
