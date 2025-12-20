{{-- Parent file: resources/views/layouts/patient-modern.blade.php --}}
@extends('layouts.patient-modern')

@section('title', 'Theo dõi thai kỳ')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Theo dõi thai kỳ, sức khỏe</h4>
            <div class="text-muted">Ghi nhận chỉ số và theo dõi tiến triển</div>
        </div>
        <a class="btn btn-primary" href="{{ route('patient.theodoithaiky.create') }}">+ Tạo bản ghi</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Tổng bản ghi</div>
                    <div class="fs-4 fw-bold">{{ $stats['total'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Đã gửi</div>
                    <div class="fs-4 fw-bold">{{ $stats['submitted'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Đã duyệt</div>
                    <div class="fs-4 fw-bold">{{ $stats['reviewed'] ?? 0 }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted small">Đã ghi nhận</div>
                    <div class="fs-4 fw-bold">{{ $stats['recorded'] ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="submitted" {{ request('status')=='submitted' ? 'selected' : '' }}>Đã gửi</option>
                        <option value="reviewed" {{ request('status')=='reviewed' ? 'selected' : '' }}>Đã duyệt</option>
                        <option value="recorded" {{ request('status')=='recorded' ? 'selected' : '' }}>Đã ghi nhận</option>
                        <option value="archived" {{ request('status')=='archived' ? 'selected' : '' }}>Lưu trữ</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-outline-primary w-100" type="submit">Lọc</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($records->isEmpty())
                <div class="text-muted">Chưa có bản ghi theo dõi.</div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Tuần thai</th>
                                <th>Cân nặng</th>
                                <th>Huyết áp</th>
                                <th>Trạng thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $r)
                                <tr>
                                    <td>{{ $r->ngay_theo_doi?->format('d/m/Y') ?? $r->created_at?->format('d/m/Y') }}</td>
                                    <td>{{ $r->tuan_thai ?? '---' }}</td>
                                    <td>{{ $r->can_nang_kg ? $r->can_nang_kg.' kg' : '---' }}</td>
                                    <td>
                                        @if($r->huyet_ap_tam_thu && $r->huyet_ap_tam_truong)
                                            {{ $r->huyet_ap_tam_thu }}/{{ $r->huyet_ap_tam_truong }}
                                        @else
                                            ---
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $r->trang_thai_badge_class }}">{{ $r->trang_thai_text }}</span>
                                        @if($r->has_canh_bao)
                                            <span class="badge {{ $r->canh_bao_badge_class }} ms-1" title="{{ $r->canh_bao_summary }}">Cảnh báo</span>
                                        @else
                                            <span class="badge {{ $r->canh_bao_badge_class }} ms-1">Ổn</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('patient.theodoithaiky.show', $r) }}">Xem</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $records->links() }}
            @endif
        </div>
    </div>
</div>
@endsection
