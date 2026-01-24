@extends('layouts.patient-modern')

@section('title', 'Kết quả Nội soi')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold text-dark">Kết quả Nội soi</h4>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($noiSois->count() === 0)
                <div class="text-muted">Chưa có chỉ định nội soi.</div>
            @else
                <div class="table-responsive">
                    <table id="patientNoiSoiTable" class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Loại</th>
                                <th>Bệnh án</th>
                                <th>Trạng thái</th>
                                <th>Ngày</th>
                                <th class="text-end">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($noiSois as $item)
                                <tr>
                                    <td>#{{ $item->id }}</td>
                                    <td>{{ $item->loaiNoiSoi?->ten ?? $item->loai }}</td>
                                    <td>#BA{{ $item->benh_an_id }}</td>
                                    <td><span class="badge bg-secondary">{{ $item->trang_thai_text }}</span></td>
                                    <td>{{ $item->created_at?->format('d/m/Y H:i') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('patient.noisoi.show', $item) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                        @if($item->hasResult())
                                            <a href="{{ $item->getDownloadUrl() }}" class="btn btn-sm btn-success" target="_blank">Tải file</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $noiSois->links() }}
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
    if ($.fn.DataTable.isDataTable('#patientNoiSoiTable')) return;

    const dt = $('#patientNoiSoiTable').DataTable({
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
    $(window).on('resize.patientNoiSoiTable', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            dt.columns.adjust();
        }, 150);
    });
});
</script>
@endpush
