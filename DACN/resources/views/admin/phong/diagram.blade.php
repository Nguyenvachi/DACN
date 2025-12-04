@extends('layouts.admin')

{{--
    Sơ đồ phòng khám realtime
    Parent file: resources/views/admin/phong/diagram.blade.php
--}}

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-diagram-3 text-primary me-2"></i>
            Sơ đồ phòng khám
        </h2>

        <div class="btn-group">
            <a href="{{ route('admin.phong.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-list"></i> Danh sách
            </a>
            <button class="btn btn-primary btn-sm" id="btnRefresh">
                <i class="bi bi-arrow-clockwise"></i> Refresh
            </button>
        </div>
    </div>

    {{-- LEGEND --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-semibold mb-3">Chú thích:</h6>
            <div class="d-flex flex-wrap gap-3">
                <span class="badge bg-success p-2"><i class="bi bi-check-circle me-1"></i> Sẵn sàng</span>
                <span class="badge bg-primary p-2"><i class="bi bi-hourglass-split me-1"></i> Đang sử dụng</span>
                <span class="badge bg-warning p-2"><i class="bi bi-tools me-1"></i> Bảo trì</span>
                <span class="badge bg-danger p-2"><i class="bi bi-x-circle me-1"></i> Tạm ngưng</span>
            </div>
        </div>
    </div>

    {{-- SƠ ĐỒ THEO TẦNG/VỊ TRÍ --}}
    @if($roomsByFloor->isNotEmpty())
        @foreach($roomsByFloor as $floor => $rooms)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-building me-2"></i>
                        {{ $floor ?: 'Chưa phân vị trí' }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($rooms as $phong)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="room-card card h-100 border-{{ $phong->status_badge }}"
                                     data-room-id="{{ $phong->id }}">
                                    <div class="card-body">
                                        {{-- Header --}}
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="fw-bold mb-0">{{ $phong->ten }}</h6>
                                            <span class="badge bg-{{ $phong->status_badge }} room-status">
                                                <i class="bi {{ $phong->status_icon }}"></i>
                                            </span>
                                        </div>

                                        {{-- Info --}}
                                        <div class="text-muted small mb-2">
                                            @if($phong->loai)
                                                <div><i class="bi bi-tag me-1"></i> {{ $phong->loai }}</div>
                                            @endif
                                            @if($phong->dien_tich)
                                                <div><i class="bi bi-rulers me-1"></i> {{ $phong->dien_tich }} m²</div>
                                            @endif
                                            @if($phong->suc_chua)
                                                <div><i class="bi bi-people me-1"></i> {{ $phong->suc_chua }} người</div>
                                            @endif
                                        </div>

                                        {{-- Bác sĩ --}}
                                        @if($phong->bacSis->isNotEmpty())
                                            <div class="border-top pt-2 mt-2">
                                                <div class="small text-muted mb-1">Bác sĩ:</div>
                                                @foreach($phong->bacSis->take(2) as $bs)
                                                    <div class="small">
                                                        <i class="bi bi-person-check text-primary me-1"></i>
                                                        {{ $bs->ho_ten }}
                                                    </div>
                                                @endforeach
                                                @if($phong->bacSis->count() > 2)
                                                    <div class="small text-muted">
                                                        +{{ $phong->bacSis->count() - 2 }} khác
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Actions --}}
                                        <div class="border-top pt-2 mt-2">
                                            <div class="btn-group w-100">
                                                <button class="btn btn-sm btn-outline-primary btn-change-status"
                                                        data-room-id="{{ $phong->id }}">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                </button>
                                                <a href="{{ route('admin.phong.statistics', $phong) }}"
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-graph-up"></i>
                                                </a>
                                                <a href="{{ route('admin.phong.edit', $phong) }}"
                                                   class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @else
        {{-- EMPTY STATE --}}
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-door-closed fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">Chưa có phòng nào</h5>
                <a href="{{ route('admin.phong.create') }}" class="btn btn-primary mt-3">
                    <i class="bi bi-plus-lg"></i> Thêm phòng đầu tiên
                </a>
            </div>
        </div>
    @endif

</div>

{{-- Modal đổi trạng thái --}}
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đổi trạng thái phòng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modalRoomId">
                <div class="mb-3">
                    <label class="form-label">Tên phòng</label>
                    <input type="text" class="form-control" id="modalRoomName" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Trạng thái mới</label>
                    <select class="form-select" id="modalStatus">
                        <option value="Sẵn sàng">Sẵn sàng</option>
                        <option value="Đang sử dụng">Đang sử dụng</option>
                        <option value="Bảo trì">Bảo trì</option>
                        <option value="Tạm ngưng">Tạm ngưng</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="btnSaveStatus">Lưu</button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .room-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .room-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15) !important;
    }
    .border-success { border-left: 4px solid #198754 !important; }
    .border-primary { border-left: 4px solid #0d6efd !important; }
    .border-warning { border-left: 4px solid #ffc107 !important; }
    .border-danger { border-left: 4px solid #dc3545 !important; }
</style>
@endpush

@push('scripts')
<script>
(function() {
    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    let currentRoomId = null;

    // Refresh toàn bộ
    document.getElementById('btnRefresh').addEventListener('click', () => {
        location.reload();
    });

    // Click nút đổi trạng thái
    document.querySelectorAll('.btn-change-status').forEach(btn => {
        btn.addEventListener('click', async function() {
            const roomId = this.dataset.roomId;
            const card = this.closest('.room-card');
            const roomName = card.querySelector('h6').textContent;

            currentRoomId = roomId;
            document.getElementById('modalRoomId').value = roomId;
            document.getElementById('modalRoomName').value = roomName;

            // Lấy trạng thái hiện tại
            try {
                const res = await fetch(`/admin/phong/${roomId}/status`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                document.getElementById('modalStatus').value = data.trang_thai_db;
            } catch (e) {
                console.error(e);
            }

            statusModal.show();
        });
    });

    // Lưu trạng thái mới
    document.getElementById('btnSaveStatus').addEventListener('click', async function() {
        const roomId = document.getElementById('modalRoomId').value;
        const newStatus = document.getElementById('modalStatus').value;

        try {
            const res = await fetch(`/admin/phong/${roomId}/update-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ trang_thai: newStatus })
            });

            const data = await res.json();

            if (data.success) {
                statusModal.hide();
                location.reload(); // Reload để cập nhật UI
            } else {
                alert('Có lỗi xảy ra!');
            }
        } catch (e) {
            console.error(e);
            alert('Không thể kết nối server');
        }
    });

    // Auto refresh mỗi 30 giây
    setInterval(() => {
        location.reload();
    }, 30000);
})();
</script>
@endpush
@endsection
