{{-- filepath: resources/views/patient/sieuam/index.blade.php --}}
{{-- Parent file: app/Http/Controllers/Patient/SieuAmController.php --}}

@extends('layouts.patient-modern')

@section('title', 'Kết quả Siêu âm của tôi')

@section('content')
<div class="container-fluid py-4">
    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-clipboard-list text-primary fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Tổng số</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-hourglass-half text-warning fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Đang chờ</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['pending'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success bg-opacity-10 rounded-3 p-3">
                            <i class="fas fa-check-circle text-success fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Đã hoàn thành</h6>
                            <h3 class="mb-0 fw-bold">{{ $stats['completed'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('patient.sieuam.index') }}" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Trạng thái</label>
                    <select name="trang_thai" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="pending" {{ request('trang_thai') == 'pending' ? 'selected' : '' }}>Chờ thực hiện</option>
                        <option value="processing" {{ request('trang_thai') == 'processing' ? 'selected' : '' }}>Đang thực hiện</option>
                        <option value="completed" {{ request('trang_thai') == 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                    </select>
                </div>

                <div class="col-md-6 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                    <a href="{{ route('patient.sieuam.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Danh sách siêu âm --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0"><i class="fas fa-list-ul me-2"></i>Danh sách siêu âm</h5>
        </div>
        <div class="card-body p-0">
            @if($sieuAms->count() > 0)
            <div class="table-responsive">
                <table id="patientSieuAmTable" class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ngày</th>
                            <th>Loại siêu âm</th>
                            <th>Bác sĩ</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sieuAms as $sieuAm)
                        <tr>
                            <td>{{ $sieuAm->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <strong>{{ $sieuAm->loaiSieuAm->ten ?? 'N/A' }}</strong>
                                @if($sieuAm->mo_ta)
                                <br><small class="text-muted">{{ Str::limit($sieuAm->mo_ta, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($sieuAm->bacSi)
                                    {{ $sieuAm->bacSi->ho_ten ?? optional($sieuAm->bacSi->user)->name ?? 'N/A' }}
                                    @php
                                        $ckName = optional($sieuAm->bacSi->chuyenKhoa)->ten ?? ($sieuAm->bacSi->chuyen_khoa ?? '');
                                    @endphp
                                    @if(!empty($ckName))
                                        <br><small class="text-muted">{{ $ckName }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">Chưa chỉ định</span>
                                @endif
                            </td>
                            <td>{{ number_format($sieuAm->gia, 0, ',', '.') }} VNĐ</td>
                            <td>
                                <span class="badge {{ $sieuAm->trang_thai_badge_class }}">
                                    {{ $sieuAm->trang_thai_text }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('patient.sieuam.show', $sieuAm) }}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($sieuAm->trang_thai === 'completed' && $sieuAm->file_path)
                                <a href="{{ URL::temporarySignedRoute('patient.benhan.sieuam.download', now()->addMinutes(10), ['sieuAm' => $sieuAm->id]) }}"
                                   class="btn btn-sm btn-outline-success"
                                   title="Tải file kết quả">
                                    <i class="fas fa-download"></i>
                                </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $sieuAms->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <p class="text-muted">Chưa có kết quả siêu âm nào</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    if (!window.jQuery || !$.fn.DataTable) return;
    if ($.fn.DataTable.isDataTable('#patientSieuAmTable')) return;

    const dt = $('#patientSieuAmTable').DataTable({
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
    $(window).on('resize.patientSieuAmTable', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            dt.columns.adjust();
        }, 150);
    });
});
</script>
@endpush
