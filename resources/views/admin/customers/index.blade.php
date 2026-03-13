@extends('layouts.admin')

@section('title', 'Khách hàng')

@section('content')
<h1 class="h3 mb-3">Khách hàng</h1>

<div class="panel p-4 mb-3">
    <form method="get" class="row g-2 align-items-end">
        <div class="col-lg-3 col-md-6">
            <label class="form-label">Tìm kiếm</label>
            <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="Tên, email, số điện thoại">
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="form-label">Segment</label>
            <select class="form-select" name="segment">
                <option value="">Tất cả</option>
                @foreach($segments as $segment)
                    <option value="{{ $segment }}" @selected(request('segment') === $segment)>{{ strtoupper($segment) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="form-label">Mức quan tâm</label>
            <select class="form-select" name="interest_level">
                <option value="">Tất cả</option>
                <option value="high" @selected(request('interest_level') === 'high')>Cao (>=70%)</option>
                <option value="medium" @selected(request('interest_level') === 'medium')>Trung bình (40-69%)</option>
                <option value="low" @selected(request('interest_level') === 'low')>Thấp (<40%)</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="form-label">Danh mục yêu thích</label>
            <select class="form-select" name="favorite_category_id">
                <option value="">Tất cả</option>
                @foreach($favoriteCategories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('favorite_category_id') === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="form-label">Sắp xếp</label>
            <select class="form-select" name="sort_by">
                <option value="">Mới nhất</option>
                <option value="retention_desc" @selected(request('sort_by') === 'retention_desc')>Retention cao nhất</option>
                <option value="engagement_desc" @selected(request('sort_by') === 'engagement_desc')>Quan tâm cao nhất</option>
                <option value="purchase_desc" @selected(request('sort_by') === 'purchase_desc')>Mua hàng cao nhất</option>
            </select>
        </div>
        <div class="col-lg-1 col-md-6 d-grid">
            <button class="btn btn-primary">Lọc</button>
        </div>
    </form>
</div>

<div class="panel p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr>
                <th>Khách hàng</th>
                <th>Segment</th>
                <th>Retention</th>
                <th>Điểm quan tâm</th>
                <th>Điểm mua hàng</th>
                <th>Danh mục yêu thích</th>
                <th>Tương tác</th>
                <th class="text-end">Thao tác</th>
            </tr>
            </thead>
            <tbody>
            @forelse($customers as $customer)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $customer->name }}</div>
                        <small class="text-secondary">{{ $customer->email }}</small>
                    </td>
                    <td>{{ strtoupper($customer->customerScore->segment ?? 'cold') }}</td>
                    <td>{{ $customer->customerScore->retention_probability ?? 0 }}%</td>
                    <td>{{ number_format($customer->customerScore->engagement_score ?? 0, 2, ',', '.') }}</td>
                    <td>{{ number_format($customer->customerScore->purchase_score ?? 0, 2, ',', '.') }}</td>
                    <td>{{ $customer->customerScore->favoriteCategory->name ?? '-' }}</td>
                    <td>{{ $customer->behavior_events_count }} hành vi / {{ $customer->orders_count }} đơn</td>
                    <td class="text-end">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.customers.show', $customer) }}">Chi tiết</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-secondary py-4">Không có dữ liệu phù hợp với bộ lọc.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $customers->links() }}
</div>
@endsection
