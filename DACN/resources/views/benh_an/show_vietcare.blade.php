@php
    $role = $role ?? (auth()->check() ? auth()->user()->roleKey() : 'patient');
    $benhAn = $record ?? ($benhAn ?? null);
@endphp

@extends(
    match ($role) {
        'admin' => 'layouts.admin',
        'doctor' => 'layouts.doctor',
        'staff' => 'layouts.staff',
        'patient' => 'layouts.patient-modern',
        default => 'layouts.app',
    }
)

@section('content')
    <div class="container-fluid py-4">
        <div class="row g-4">
            {{-- CỘT TRÁI: NỘI DUNG CHÍNH (8 phần) --}}
            <div class="col-lg-8">
                {{-- 1. Card Thông tin Khám --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">
                                <i class="fas fa-file-medical-alt me-2"></i>Chi tiết bệnh án
                            </h5>
                            <span class="badge bg-success">
                                {{ $benhAn->lichHen->trang_thai ?? 'Hoàn thành' }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        {{-- Header: Mã & Ngày --}}
                        <div class="row mb-4">
                            <div class="col-6">
                                <small class="text-muted text-uppercase fw-bold">Tiêu đề / Mã bệnh án</small>
                                <div class="fs-5 fw-bold text-dark">{{ $benhAn->tieu_de ?? 'Bệnh án #' . $benhAn->id }}
                                </div>
                                <div class="small text-muted">ID: #{{ $benhAn->id }}</div>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted text-uppercase fw-bold">Ngày khám</small>
                                <div class="fs-5 text-dark">
                                    {{ optional($benhAn->ngay_kham)->format('d/m/Y') }}
                                    <small
                                        class="text-muted fw-normal">({{ optional($benhAn->ngay_kham)->format('H:i') }})</small>
                                </div>
                            </div>
                        </div>

                        {{-- Block Thông tin Bác sĩ & Dịch vụ (Icon tròn) --}}
                        <div class="bg-light p-3 rounded mb-4 border">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white p-2 rounded-circle shadow-sm me-3 text-primary">
                                            <i class="fas fa-user-md fa-lg"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Bác sĩ phụ trách</small>
                                            <span class="fw-bold text-dark">
                                                {{ $benhAn->bacSi->ho_ten ?? '---' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white p-2 rounded-circle shadow-sm me-3 text-info">
                                            <i class="fas fa-hospital-alt fa-lg"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Dịch vụ / Chuyên khoa</small>
                                            <span class="fw-bold text-dark">
                                                {{ $benhAn->dichVu->ten_dich_vu ?? 'Khám tổng quát' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="text-muted opacity-25">

                        {{-- NỘI DUNG LÂM SÀNG (Bỏ tab, hiện dọc) --}}
                        <h6 class="mb-3 text-uppercase text-muted small fw-bold">
                            <i class="fas fa-clipboard-list me-2"></i>Kết quả lâm sàng
                        </h6>

                        <div class="mb-3">
                            <label class="fw-bold text-dark">Triệu chứng:</label>
                            <p class="text-muted">{{ $benhAn->trieu_chung ?? 'Không ghi nhận' }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold text-danger">Chẩn đoán:</label>
                            <div
                                class="alert alert-light border-start border-danger border-3 shadow-sm text-danger fw-bold">
                                {{ $benhAn->chuan_doan ?? 'Chưa có chẩn đoán' }}
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold text-dark">Hướng điều trị:</label>
                            <p class="text-muted">{{ $benhAn->dieu_tri ?? 'Theo chỉ định bác sĩ' }}</p>
                        </div>

                        @if ($benhAn->ghi_chu)
                            <div class="mb-0">
                                <label class="fw-bold text-dark">Ghi chú:</label>
                                <p class="fst-italic text-muted small bg-light p-2 rounded">{{ $benhAn->ghi_chu }}</p>
                            </div>
                        @endif

                        {{-- Include phần lâm sàng mở rộng nếu có --}}
                        @includeIf('benh_an.partials._clinical')
                    </div>
                </div>

                {{-- 2. Card Đơn thuốc --}}
                @if ($benhAn->donThuocs && $benhAn->donThuocs->isNotEmpty())
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0 text-success"><i class="fas fa-prescription-bottle-alt me-2"></i>Đơn thuốc</h5>
                        </div>
                        <div class="card-body p-0">
                            @foreach ($benhAn->donThuocs as $don)
                                <div class="p-3 border-bottom bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <strong>Đơn thuốc #{{ $don->id }}</strong>
                                        <span class="text-muted small">{{ $don->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="text-secondary">
                                            <tr>
                                                <th class="ps-4">Tên thuốc</th>
                                                <th class="text-center">Số lượng</th>
                                                <th>Cách dùng/Liều lượng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($don->items as $it)
                                                <tr>
                                                    <td class="ps-4 fw-bold text-dark">
                                                        {{ $it->thuoc->ten ?? 'Thuốc #' . $it->thuoc_id }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-secondary">{{ $it->so_luong }}</span>
                                                    </td>
                                                    <td class="text-muted small">
                                                        {{ $it->lieu_dung ?? ($it->cach_dung ?? 'Theo chỉ dẫn') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- CỘT PHẢI: BÊN LỀ (4 phần) --}}
            <div class="col-lg-4">
                {{-- 1. Card Bệnh nhân --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-user-circle me-2"></i>Thông tin bệnh nhân</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            {{-- Avatar chữ cái --}}
                            <div class="avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                style="width:50px;height:50px;font-size:20px;font-weight:bold;">
                                {{ strtoupper(substr($benhAn->benhNhan->name ?? 'BN', 0, 1)) }}
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $benhAn->benhNhan->name ?? '---' }}</h6>
                                <small class="text-muted">{{ $benhAn->benhNhan->email ?? 'Chưa cập nhật email' }}</small>
                            </div>
                        </div>
                        <hr class="my-3">
                        <p class="mb-2 d-flex justify-content-between">
                            <span class="text-muted">Số điện thoại:</span>
                            <span class="fw-bold">{{ $benhAn->benhNhan->so_dien_thoai ?? '---' }}</span>
                        </p>
                        <p class="mb-0 d-flex justify-content-between">
                            <span class="text-muted">Ngày sinh:</span>
                            <span class="fw-bold">
                                {{ optional($benhAn->benhNhan->ngay_sinh)->format('d/m/Y') ?? '---' }}
                            </span>
                        </p>
                    </div>
                </div>

                {{-- 2. Card Hành động --}}
                <div class="card shadow-sm sticky-top" style="top: 20px; z-index: 1;">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-tasks me-2"></i>Tác vụ</h6>
                    </div>
                    <div class="card-body d-grid gap-2">
                        {{-- Nút Export PDF --}}
                        @if (\Illuminate\Support\Facades\Route::has($role . '.benhan.exportPdf'))
                            <a href="{{ route($role . '.benhan.exportPdf', $benhAn) }}" class="btn btn-primary">
                                <i class="fas fa-file-pdf me-2"></i>Xuất PDF Bệnh án
                            </a>
                        @endif

                        {{-- Nút Chỉnh sửa (Admin/Doctor) --}}
                        @if (in_array($role, ['admin', 'doctor']))
                            <a href="{{ route($role . '.benhan.edit', $benhAn) }}" class="btn btn-outline-warning">
                                <i class="fas fa-edit me-2"></i>Chỉnh sửa bệnh án
                            </a>
                            <a href="{{ route('benhan.donthuoc.create', $benhAn) }}" class="btn btn-outline-success">
                                <i class="fas fa-plus me-2"></i>Kê đơn thuốc
                            </a>
                        @endif

                        {{-- Liên kết hóa đơn --}}
                        @if ($benhAn->lichHen && $benhAn->lichHen->hoaDon)
                            <div class="alert alert-info mt-2 mb-0 p-2 text-center small">
                                <i class="fas fa-receipt me-1"></i>Đã có hóa đơn:
                                <strong>#{{ $benhAn->lichHen->hoaDon->id }}</strong>
                                <br>
                                (Tổng: {{ number_format($benhAn->lichHen->hoaDon->tong_tien ?? 0) }}đ)
                            </div>
                        @endif

                        <a href="{{ url()->previous() }}" class="btn btn-link text-decoration-none text-muted">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                        </a>
                    </div>
                </div>

                {{-- 3. Card Tệp đính kèm --}}
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold"><i class="fas fa-paperclip me-2"></i>Tệp đính kèm</h6>
                    </div>
                    <div class="card-body p-0">
                        {{-- Include phần tệp đính kèm (giữ nguyên logic cũ) --}}
                        <div class="p-3">
                            @includeIf('benh_an.partials._attachments')
                        </div>
                    </div>
                </div>

                {{-- 4. Audit Log (Hoạt động) --}}
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="mb-0 fw-bold text-muted small text-uppercase">Hoạt động gần đây</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled small mb-0">
                            @forelse($benhAn->audits->take(5) as $a)
                                <li class="mb-2 text-muted">
                                    <i class="fas fa-clock me-1 text-secondary"></i>
                                    {{ $a->created_at->format('d/m H:i') }} — {{ $a->action }}
                                    @if ($a->user)
                                        bởi <strong>{{ $a->user->name }}</strong>
                                    @endif
                                </li>
                            @empty
                                <li class="text-muted fst-italic">Chưa có hoạt động ghi nhận</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
