@php
    $role = auth()->check() ? auth()->user()->roleKey() : 'patient';

    // Mỗi role dùng layout riêng — mapping rõ ràng
    $layout = match ($role) {
        'admin' => 'layouts.admin',
        'doctor' => 'layouts.doctor',
        'staff' => 'layouts.staff',
        'patient' => 'layouts.patient-modern',
        default => 'layouts.app',
    };
@endphp

@extends($layout)

@section('content')
    <div class="container-fluid py-4">

        {{-- TIÊU ĐỀ + LỊCH SỬ --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">
                <i class="bi bi-journal-medical text-primary"></i>
                Chi tiết bệnh án
            </h3>

            @php
                $benhAn = $record;
                $actionButtons = '';
                if (auth()->check()) {
                    // History button for admin and doctor
                    if (in_array($role, ['admin', 'doctor'])) {
                        $actionButtons .= '<a href="' . route($role . '.benhan.audit', $record) . '" class="btn btn-secondary d-flex align-items-center gap-2 me-2"><i class="bi bi-clock-history"></i> Lịch sử thay đổi</a>';
                    }
                }
                if (auth()->check() && $role === 'doctor' && $record->lichHen) {
                    // Start exam button
                    if ($record->lichHen->canStartExam()) {
                        $actionButtons .= '<form method="POST" action="' . route('doctor.queue.start', $record->lichHen->id) . '" class="d-inline me-2">' . csrf_field() . '<button class="btn btn-success">Bắt đầu khám</button></form>';
                    }
                    // Continue edit if benhAn exists
                    if ($record->id) {
                        $actionButtons .= '<a href="' . route('doctor.benhan.edit', $record->id) . '" class="btn btn-outline-primary">Tiếp tục khám</a>';
                    }
                    // Complete exam button
                    if ($record->lichHen->canCompleteExam()) {
                        $actionButtons .= '<form method="POST" action="' . route('doctor.lichhen.complete', $record->lichHen->id) . '" class="d-inline ms-2" onsubmit="return confirm(\'Xác nhận hoàn tất khám?\')">' . csrf_field() . '<button class="btn btn-danger">Hoàn tất khám</button></form>';
                    }
                }
            @endphp
            @include('benh_an._header', ['actionButtons' => $actionButtons, 'benhAn' => $benhAn])
        </div>

        <div class="row g-4">

            <div class="col-lg-4">
                @include('benh_an._timeline', ['benhAn' => $benhAn])
                <div class="mb-3">
                    <div class="btn-group" role="group" aria-label="Quick nav">
                        <a href="#basic" class="btn btn-sm btn-outline-secondary">Thông tin</a>
                        <a href="#vitals" class="btn btn-sm btn-outline-secondary">Vitals</a>
                        <a href="#clinical" class="btn btn-sm btn-outline-secondary">Khám</a>
                        <a href="#services" class="btn btn-sm btn-outline-secondary">Dịch vụ</a>
                        <a href="#prescriptions" class="btn btn-sm btn-outline-secondary">Đơn thuốc</a>
                        <a href="#attachments" class="btn btn-sm btn-outline-secondary">Tệp</a>
                    </div>
                </div>
                @include('benh_an.partials._vitals')
                <style>
                    .timeline .badge { min-width: 120px; }
                    .timeline .flex-fill { position: relative; }
                    .timeline .flex-fill::after { content: ''; position: absolute; height: 2px; left: 50%; right: -50%; top: 22px; background: #e9ecef; z-index: 0; }
                    .timeline .badge.bg-primary { box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
                </style>


        {{-- ===========================
        THÔNG TIN CƠ BẢN
    ============================ --}}
                <div class="card shadow-sm mb-4 border-start border-primary border-3" id="basic">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">
                    <h5 class="fw-semibold">
                        <i class="bi bi-info-circle"></i> Thông tin cơ bản
                    </h5>

                    @if (in_array($role, ['admin', 'doctor']))
                        <a href="{{ route($role . '.benhan.edit', $record) }}"
                            class="btn btn-sm btn-primary d-flex align-items-center gap-1">
                            <i class="bi bi-pencil"></i> Sửa
                        </a>
                    @endif
                </div>
                {{-- Quick actions & attachments --}}

                <div class="row g-3">
                    <div class="col-md-6"><strong>Tiêu đề:</strong> {{ $record->tieu_de }}</div>
                    <div class="col-md-6"><strong>Ngày khám:</strong> {{ $record->ngay_kham->format('d/m/Y') }}</div>
                    <div class="col-md-6"><strong>Bệnh nhân:</strong> {{ $record->benhNhan->name ?? $record->user_id }}
                    </div>
                    <div class="col-md-6"><strong>Bác sĩ:</strong> {{ $record->bacSi->ho_ten ?? $record->bac_si_id }}</div>
                </div>

            </div>
        </div>


        {{-- ===========================
        THÔNG TIN LÂM SÀNG
    ============================ --}}
        <div class="card shadow-sm mb-4 border-start border-success border-3" id="clinical">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">
                    <h5 class="fw-semibold">
                        <i class="bi bi-clipboard2-pulse"></i> Thông tin lâm sàng
                    </h5>

                    @if (in_array($role, ['admin', 'doctor']))
                        <a href="{{ route($role . '.benhan.edit', $record) }}"
                            class="btn btn-sm btn-primary d-flex align-items-center gap-1">
                            <i class="bi bi-pencil"></i> Sửa
                        </a>
                    @endif
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <h6 class="fw-semibold">Triệu chứng</h6>
                        <p>{{ $record->trieu_chung }}</p>
                    </div>

                    <div class="col-md-4">
                        <h6 class="fw-semibold">Chẩn đoán</h6>
                        <p>{{ $record->chuan_doan }}</p>
                    </div>

                    <div class="col-md-4">
                        <h6 class="fw-semibold">Điều trị</h6>
                        <p>{{ $record->dieu_tri }}</p>
                    </div>
                </div>

            </div>
        </div>
            {{-- clinical section --}}
            @include('benh_an.partials._clinical')


        {{-- ===========================
        UPLOAD FILE (ADMIN/DOCTOR)
    ============================ --}}
        @if (in_array($role, ['admin', 'doctor']))
                <div class="card shadow-sm mb-4 border-start border-warning border-3" id="attachments-upload">
                <div class="card-body">

                    <h5 class="fw-semibold mb-3">
                        <i class="bi bi-upload"></i> Tải lên tệp đính kèm
                    </h5>

                    <form action="{{ route($role . '.benhan.files.upload', $record) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <input type="file" name="files[]" multiple class="form-control mb-3">

                        <button class="btn btn-primary">
                            <i class="bi bi-cloud-arrow-up"></i> Tải lên
                        </button>
                    </form>

                </div>
            </div>
        @endif


        {{-- ===========================
        DANH SÁCH FILE
    ============================ --}}
        @include('benh_an.partials._attachments')


        {{-- ===========================
        ĐƠN THUỐC
    ============================ --}}
        <div class="card shadow-sm mb-4 border-start border-danger border-3" id="prescriptions">
            <div class="card-body">

                <h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-capsule"></i> Đơn thuốc
                </h5>

                <ul class="ms-4">
                    @forelse($benhAn->donThuocs as $dt)
                        <li class="mb-2">
                            <strong>Đơn #{{ $dt->id }}</strong>
                            — {{ $dt->created_at->format('d/m/Y') }}

                            @if ($dt->ghi_chu)
                                <span class="text-muted">({{ $dt->ghi_chu }})</span>
                            @endif

                            <ul class="ms-4 mt-1">
                                @foreach ($dt->items as $it)
                                    <li>
                                        {{ $it->thuoc->ten ?? 'Thuốc #' . $it->thuoc_id }}
                                        — SL: {{ $it->so_luong }}
                                        @if ($it->lieu_dung)
                                            | Liều: {{ $it->lieu_dung }}
                                        @endif
                                        @if ($it->cach_dung)
                                            | Cách: {{ $it->cach_dung }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @empty
                        <li class="text-muted">Chưa có đơn thuốc nào.</li>
                    @endforelse
                </ul>

                @if (in_array($role, ['admin', 'doctor']))
                    <a class="btn btn-outline-primary btn-sm mt-3" href="{{ route('benhan.donthuoc.create', $benhAn) }}">
                        + Kê đơn thuốc
                    </a>
                @endif

            </div>
        </div>


        {{-- ===========================
        KẾT QUẢ XÉT NGHIỆM
    ============================ --}}
        <div class="card shadow-sm mb-4 border-start border-info border-3">
            <div class="card-body">

                <h5 class="fw-semibold mb-3">
                    <i class="bi bi-diagram-2"></i> Kết quả xét nghiệm
                </h5>

                {{-- Form upload --}}
                @if (in_array($role, ['admin', 'doctor']))
                    <form class="row g-3 mb-3" method="POST" action="{{ route('benhan.xetnghiem.upload', $benhAn) }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-3">
                            <input name="loai" class="form-control" placeholder="Loại xét nghiệm" required>
                        </div>

                        <div class="col-md-5">
                            <input type="file" name="file" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <input name="mo_ta" class="form-control" placeholder="Mô tả (tuỳ chọn)">
                        </div>

                        <div class="col-md-1">
                            <button class="btn btn-primary w-100">Tải</button>
                        </div>
                    </form>
                @endif

                {{-- danh sách --}}
                <ul class="ms-3">
                    @forelse($benhAn->xetNghiems as $xn)
                        <li class="mb-2">
                            {{ $xn->loai }} —
                            <a class="text-primary"
                                href="{{ URL::temporarySignedRoute($role . '.benhan.xetnghiem.download', now()->addMinutes(10), ['xetNghiem' => $xn->id]) }}">
                                Xem file
                            </a>

                            @if ($xn->mo_ta)
                                <span class="text-muted"> — {{ $xn->mo_ta }}</span>
                            @endif
                        </li>

                    @empty
                        <li>Chưa có kết quả xét nghiệm</li>
                    @endforelse
                </ul>

            </div>
        </div>
            </div>

            {{-- SIDEBAR --}}
            <div class="col-lg-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-semibold">Thông tin bệnh nhân</h6>
                        <div class="mb-2"><strong>Họ & tên:</strong> {{ $benhAn->benhNhan->name ?? '—' }}</div>
                        <div class="mb-2"><strong>SĐT:</strong> {{ $benhAn->benhNhan->so_dien_thoai ?? '—' }}</div>
                        <div class="mb-2"><strong>Ngày sinh:</strong> {{ optional($benhAn->benhNhan->ngay_sinh)->format('d/m/Y') ?? '—' }}</div>
                        <hr>
                        <div class="mb-1"><strong>Bác sĩ:</strong> {{ $benhAn->bacSi->ho_ten ?? '—' }}</div>
                        <div class="mb-1"><strong>Dịch vụ:</strong> {{ $benhAn->dichVu->ten_dich_vu ?? '—' }}</div>
                        @if($benhAn->lichHen)
                            <hr>
                            <div class="mb-1"><strong>Mã lịch hẹn:</strong> {{ $benhAn->lichHen->ma_lich_hen ?? $benhAn->lichHen->id }}</div>
                            <div class="mb-1"><strong>Ngày/giờ:</strong> {{ optional($benhAn->lichHen->ngay_hen)->format('d/m/Y') }} — {{ $benhAn->lichHen->thoi_gian_hen }}</div>
                            <div class="mb-1"><strong>Trạng thái:</strong> <x-appointment-status-badge :status="$benhAn->lichHen->trang_thai" /></div>
                        @endif
                    </div>
                </div>

                {{-- Actions (Start/Complete/Export/Kê đơn) --}}
                @include('benh_an.partials._actions')

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-semibold">Hóa đơn / Thanh toán</h6>
                        @if($benhAn->lichHen && $benhAn->lichHen->hoaDon)
                            <p class="mb-1"><strong>Mã hóa đơn:</strong> #{{ $benhAn->lichHen->hoaDon->id }}</p>
                            <p class="mb-1"><strong>Tổng tiền:</strong> {{ number_format($benhAn->lichHen->hoaDon->tong_tien ?? 0, 0, ',', '.') }} đ</p>
                            <p class="mb-1"><strong>Trạng thái:</strong> {{ $benhAn->lichHen->hoaDon->trang_thai ?? '—'}}</p>
                        @else
                            <div class="text-muted">Chưa có hóa đơn</div>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h6 class="fw-semibold">Hoạt động gần đây</h6>
                        <ul class="list-unstyled small mt-2">
                            @forelse($benhAn->audits->take(10) as $a)
                                <li class="mb-2">{{ $a->created_at->format('d/m/Y H:i') }} — {{ $a->action }} @if($a->user) bởi {{ $a->user->name }}@endif</li>
                            @empty
                                <li class="text-muted">Chưa có hoạt động</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // AJAX action forms for start / complete
    document.querySelectorAll('form.ajax-action').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            if (!confirm('Xác nhận thực hiện hành động này?')) return;
            const url = this.action;
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const btn = this.querySelector('button');
            const original = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Đang xử lý...';
            try {
                const res = await fetch(url, {method: 'POST', headers: {'X-CSRF-TOKEN': token, 'Accept': 'application/json'}, credentials: 'same-origin'});
                if (res.ok) {
                    const redirect = this.getAttribute('data-success-redirect');
                    if (redirect) window.location.href = redirect;
                    else window.location.reload();
                    return;
                }
                const data = await res.json().catch(() => ({}));
                alert(data.error || data.message || 'Lỗi khi thực hiện thao tác');
            } catch (err) {
                console.error(err);
                alert('Không thể kết nối tới server.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = original;
            }
        });
    });
});
</script>

@endpush
