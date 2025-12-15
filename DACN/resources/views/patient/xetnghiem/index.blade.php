@extends('layouts.patient-modern')

@section('title', 'Kết quả xét nghiệm')
@section('page-title', 'Hồ sơ xét nghiệm')
@section('page-subtitle', 'Theo dõi lịch sử và tải về kết quả xét nghiệm của bạn')

@section('content')
    <div class="row g-4">
        {{-- PHẦN 1: THỐNG KÊ (Dashboard Mini) --}}
        <div class="col-12">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100 bg-primary bg-opacity-10">
                        <div class="card-body d-flex align-items-center justify-content-between p-4">
                            <div>
                                <p class="text-primary fw-bold mb-1 text-uppercase small">Tổng số xét nghiệm</p>
                                <h2 class="mb-0 fw-bold text-dark">{{ $xetNghiems->total() }}</h2>
                            </div>
                            <div class="bg-white rounded-circle p-3 shadow-sm text-primary">
                                <i class="fas fa-flask fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100 bg-success bg-opacity-10">
                        <div class="card-body d-flex align-items-center justify-content-between p-4">
                            <div>
                                <p class="text-success fw-bold mb-1 text-uppercase small">Tháng này</p>
                                <h2 class="mb-0 fw-bold text-dark">
                                    {{ $xetNghiems->where('created_at', '>=', now()->startOfMonth())->count() }}</h2>
                            </div>
                            <div class="bg-white rounded-circle p-3 shadow-sm text-success">
                                <i class="fas fa-calendar-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card shadow-sm border-0 h-100 bg-warning bg-opacity-10">
                        <div class="card-body d-flex align-items-center justify-content-between p-4">
                            <div>
                                <p class="text-warning-dark fw-bold mb-1 text-uppercase small">Đang chờ kết quả</p>
                                <h2 class="mb-0 fw-bold text-dark">{{ $xetNghiems->whereNull('ket_qua')->count() }}</h2>
                            </div>
                            <div class="bg-white rounded-circle p-3 shadow-sm text-warning">
                                <i class="fas fa-hourglass-half fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PHẦN 2: DANH SÁCH XÉT NGHIỆM --}}
        <div class="col-12">
            @forelse($xetNghiems as $xetNghiem)
                <div class="card shadow-sm mb-4 border-hover-primary transition-all">
                    {{-- Header Card --}}
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-3 text-info">
                                    <i class="fas fa-microscope fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">
                                        <a href="{{ route('patient.xetnghiem.show', $xetNghiem) }}"
                                            class="text-dark text-decoration-none stretched-link">
                                            {{ $xetNghiem->loai ?? 'Xét nghiệm tổng quát' }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>{{ $xetNghiem->created_at?->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>

                            {{-- Badge Trạng thái --}}
                            <div>
                                @if ($xetNghiem->ket_qua)
                                    @if ($xetNghiem->trang_thai === 'binh_thuong')
                                        <span
                                            class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success border-opacity-25">
                                            <i class="fas fa-check-circle me-1"></i>Bình thường
                                        </span>
                                    @elseif($xetNghiem->trang_thai === 'bat_thuong')
                                        <span
                                            class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill border border-danger border-opacity-25">
                                            <i class="fas fa-exclamation-triangle me-1"></i>Bất thường
                                        </span>
                                    @else
                                        <span
                                            class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill border border-info border-opacity-25">
                                            <i class="fas fa-file-alt me-1"></i>Đã có kết quả
                                        </span>
                                    @endif
                                @else
                                    <span
                                        class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill border border-warning border-opacity-25">
                                        <i class="fas fa-spinner fa-spin me-1"></i>Đang xử lý
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row g-4">
                            {{-- Cột trái: Thông tin ngữ cảnh --}}
                            <div class="col-md-7 border-end-md">
                                @if ($xetNghiem->benhAn)
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-user-md text-primary me-2" style="width: 20px;"></i>
                                            <span class="text-muted small me-2">Bác sĩ chỉ định:</span>
                                            <span
                                                class="fw-semibold">{{ $xetNghiem->benhAn->bacSi->ho_ten ?? ($xetNghiem->benhAn->bacSi->user->name ?? 'N/A') }}</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-notes-medical text-danger me-2" style="width: 20px;"></i>
                                            <span class="text-muted small me-2">Chẩn đoán sơ bộ:</span>
                                            <span class="fw-semibold">{{ $xetNghiem->benhAn->chan_doan ?? '---' }}</span>
                                        </div>
                                    </div>
                                @endif

                                @if ($xetNghiem->mo_ta)
                                    <div class="bg-light p-3 rounded mb-3">
                                        <h6 class="text-muted small fw-bold text-uppercase mb-2">Mô tả / Yêu cầu:</h6>
                                        <p class="mb-0 text-dark small">{{ $xetNghiem->mo_ta }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Cột phải: KẾT QUẢ QUAN TRỌNG --}}
                            <div class="col-md-5">
                                @if ($xetNghiem->ket_qua)
                                    <div class="h-100 d-flex flex-column">
                                        <div class="alert alert-light border shadow-sm flex-grow-1">
                                            <h6 class="alert-heading fw-bold text-primary mb-2">
                                                <i class="fas fa-clipboard-check me-2"></i>KẾT QUẢ:
                                            </h6>
                                            <p class="lead fw-bold mb-0 text-dark" style="white-space: pre-line;">
                                                {{ $xetNghiem->ket_qua }}
                                            </p>
                                        </div>

                                        @if ($xetNghiem->nhan_xet)
                                            <div
                                                class="alert alert-warning border-warning bg-warning bg-opacity-10 mt-2 mb-0">
                                                <small class="fw-bold d-block mb-1 text-warning-dark">Nhận xét của bác
                                                    sĩ:</small>
                                                <span class="small text-dark">{{ $xetNghiem->nhan_xet }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div
                                        class="h-100 d-flex flex-column align-items-center justify-content-center text-center text-muted p-4 bg-light rounded border border-dashed">
                                        <i class="fas fa-hourglass-half fa-3x mb-3 text-warning opacity-50"></i>
                                        <h6>Chưa có kết quả</h6>
                                        <small>Vui lòng quay lại sau hoặc liên hệ phòng khám.</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Footer: Actions --}}
                    <div class="card-footer bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="small text-muted">
                                <strong>Mã mẫu:</strong> #{{ $xetNghiem->id }}
                            </div>
                            <div class="d-flex gap-2">
                                {{-- Nút tải file đính kèm (Nếu có) --}}
                                @if ($xetNghiem->file_ket_qua)
                                    <a href="{{ route('patient.benhan.xetnghiem.download', $xetNghiem) }}"
                                        class="btn btn-primary btn-sm">
                                        <i class="fas fa-download me-1"></i>Tải file gốc (PDF/Ảnh)
                                    </a>
                                @endif

                                {{-- Nút in kết quả --}}
                                <button onclick="printResult('{{ $xetNghiem->id }}')"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-print me-1"></i>In kết quả
                                </button>

                                {{-- Link về bệnh án --}}
                                @if ($xetNghiem->benhAn)
                                    <a href="{{ route('patient.benhan.show', $xetNghiem->benhAn) }}"
                                        class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-file-medical me-1"></i>Xem bệnh án
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="mb-3">
                        <span class="fa-stack fa-2x">
                            <i class="fas fa-circle fa-stack-2x text-light"></i>
                            <i class="fas fa-flask fa-stack-1x text-secondary opacity-50"></i>
                        </span>
                    </div>
                    <h5 class="text-muted fw-bold">Chưa có dữ liệu xét nghiệm</h5>
                    <p class="text-muted mb-4">Các chỉ định xét nghiệm của bạn sẽ hiển thị tại đây.</p>
                    <a href="{{ route('patient.benhan.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại hồ sơ bệnh án
                    </a>
                </div>
            @endforelse

            {{-- Pagination --}}
            @if ($xetNghiems->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $xetNghiems->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function printResult(id) {
                window.print();
            }
        </script>
    @endpush

    @push('styles')
        <style>
            .border-hover-primary:hover {
                border-color: var(--bs-primary) !important;
            }

            .transition-all {
                transition: all 0.3s ease;
            }

            @media (min-width: 768px) {
                .border-end-md {
                    border-right: 1px solid #dee2e6;
                }
            }

            @media print {
                body * {
                    visibility: hidden;
                }

                .card,
                .card * {
                    visibility: visible;
                }

                .card {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    border: none !important;
                    box-shadow: none !important;
                }

                .btn,
                .badge,
                footer,
                header,
                .col-md-4 {
                    display: none !important;
                }

                /* Ẩn nút và cột thống kê khi in */
            }
        </style>
    @endpush
@endsection
