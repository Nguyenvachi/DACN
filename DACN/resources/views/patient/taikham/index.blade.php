{{-- Parent file: resources/views/layouts/patient-modern.blade.php --}}
@extends('layouts.patient-modern')

@section('title', 'Tái khám')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0 fw-bold">Tái khám</h4>
            <div class="text-muted">Yêu cầu tái khám và lịch tái khám</div>
        </div>
        <a class="btn btn-primary" href="{{ route('patient.taikham.create') }}">+ Gửi yêu cầu</a>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Tổng</div><div class="fs-4 fw-bold">{{ $stats['total'] ?? 0 }}</div></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Chờ xác nhận</div><div class="fs-4 fw-bold">{{ $stats['pending'] ?? 0 }}</div></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Đã xác nhận</div><div class="fs-4 fw-bold">{{ $stats['confirmed'] ?? 0 }}</div></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted small">Đã đặt lịch</div><div class="fs-4 fw-bold">{{ $stats['booked'] ?? 0 }}</div></div></div></div>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="">-- Tất cả --</option>
                        <option value="Chờ xác nhận" {{ request('status')=='Chờ xác nhận' ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="Đã xác nhận" {{ request('status')=='Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="Đã đặt lịch" {{ request('status')=='Đã đặt lịch' ? 'selected' : '' }}>Đã đặt lịch</option>
                        <option value="Hoàn thành" {{ request('status')=='Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="Đã hủy" {{ request('status')=='Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button class="btn btn-outline-primary w-100" type="submit">Lọc</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($records->isEmpty())
                <div class="text-muted">Chưa có yêu cầu tái khám.</div>
            @else
                <div class="table-responsive">
                    <table id="patientTaiKhamTable" class="table align-middle">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Giờ</th>
                                <th>Trạng thái</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $r)
                                <tr>
                                    <td>{{ $r->ngay_tai_kham?->format('d/m/Y') ?? '---' }}</td>
                                    <td>{{ $r->thoi_gian_tai_kham ? \Carbon\Carbon::parse($r->thoi_gian_tai_kham)->format('H:i') : '---' }}</td>
                                    <td><span class="badge {{ $r->trang_thai_badge_class }}">{{ $r->trang_thai_text }}</span></td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('patient.taikham.show', $r) }}">Xem</a>
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

@push('scripts')
<script>
$(document).ready(function() {
    if (!window.jQuery || !$.fn.DataTable) return;
    if ($.fn.DataTable.isDataTable('#patientTaiKhamTable')) return;

    const dt = $('#patientTaiKhamTable').DataTable({
        language: {
            sProcessing: 'Đang xử lý...',
            sLengthMenu: 'Hiển thị _MENU_ dòng',
            sZeroRecords: 'Không tìm thấy dữ liệu',
            sInfo: 'Hiển thị _START_ đến _END_ trong tổng số _TOTAL_ dòng',
            sInfoEmpty: 'Hiển thị 0 đến 0 trong tổng số 0 dòng',
            sInfoFiltered: '(lọc từ _MAX_ dòng)',
            sSearch: 'Tìm kiếm:',
            oPaginate: { sFirst: 'Đầu', sPrevious: 'Trước', sNext: 'Tiếp', sLast: 'Cuối' }
        },
        responsive: false,
        scrollX: true,
        autoWidth: true,
        paging: false,
        info: false,
        searching: false,
        lengthChange: false,
        order: [],
        columnDefs: [{ orderable: false, targets: -1 }]
    });

    setTimeout(function() {
        dt.columns.adjust();
    }, 0);

    let resizeTimer;
    $(window).on('resize.patientTaiKhamTable', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            dt.columns.adjust();
        }, 150);
    });
});
</script>
@endpush
