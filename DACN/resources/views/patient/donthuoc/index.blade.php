@extends('layouts.patient-modern')

@section('title', 'Đơn thuốc của tôi')
@section('page-title', 'Đơn thuốc cá nhân')
@section('page-subtitle', 'Theo dõi lịch sử dùng thuốc và chỉ định của bác sĩ')

@section('content')
    <div class="row g-4">
        <div class="col-12">
            {{-- Bộ lọc tìm kiếm (Optional - Giữ layout gọn gàng) --}}
            <div class="card shadow-sm mb-4 border-0 bg-primary bg-gradient text-white">
                <div class="card-body p-4 d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-1 fw-bold"><i class="fas fa-pills me-2"></i>Danh sách đơn thuốc</h5>
                        <p class="mb-0 opacity-75 small">Toàn bộ đơn thuốc đã được bác sĩ kê cho bạn</p>
                    </div>
                    <div class="d-none d-md-block">
                        <i class="fas fa-file-prescription fa-3x opacity-50"></i>
                    </div>
                </div>
            </div>

            {{-- Danh sách đơn thuốc --}}
            @forelse($donThuocs as $donThuoc)
                <div class="card shadow-sm mb-4 border-hover-primary transition-all">
                    {{-- Header Card --}}
                    <div class="card-header bg-white py-3 border-bottom">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded-circle p-2 me-3 text-success">
                                    <i class="fas fa-prescription fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">
                                        <a href="{{ route('patient.donthuoc.show', $donThuoc) }}"
                                            class="text-dark text-decoration-none stretched-link">
                                            Đơn thuốc #{{ $donThuoc->id }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="far fa-clock me-1"></i>{{ $donThuoc->created_at?->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>

                            {{-- Badge Trạng thái --}}
                            <div>
                                @if ($donThuoc->trang_thai === 'da_cap_thuoc')
                                    <span
                                        class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill border border-success border-opacity-25">
                                        <i class="fas fa-check-circle me-1"></i>Đã cấp thuốc
                                    </span>
                                @else
                                    <span
                                        class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill border border-warning border-opacity-25">
                                        <i class="fas fa-hourglass-half me-1"></i>Chờ lấy thuốc
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        {{-- Thông tin ngữ cảnh (Bác sĩ, Chẩn đoán) --}}
                        @if ($donThuoc->benhAn)
                            <div class="bg-light p-3 border-bottom">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-md text-primary me-2"></i>
                                            <div>
                                                <span class="text-muted small d-block">Bác sĩ kê đơn</span>
                                                <span
                                                    class="fw-semibold">{{ $donThuoc->benhAn->bacSi->ho_ten ?? ($donThuoc->benhAn->bacSi->user->name ?? 'N/A') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-stethoscope text-danger me-2"></i>
                                            <div>
                                                <span class="text-muted small d-block">Chẩn đoán</span>
                                                <span
                                                    class="fw-semibold">{{ \Illuminate\Support\Str::limit($donThuoc->benhAn->chan_doan ?? 'N/A', 50) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Bảng thuốc --}}
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-white text-secondary small text-uppercase">
                                    <tr>
                                        <th class="ps-4" style="width: 40%">Tên thuốc / Hoạt chất</th>
                                        <th style="width: 20%">Liều lượng</th>
                                        <th class="text-center" style="width: 15%">Số lượng</th>
                                        <th style="width: 25%">Cách dùng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($donThuoc->items as $item)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark">{{ $item->ten_thuoc }}</div>
                                                @if ($item->thuoc && $item->thuoc->hoat_chat)
                                                    <small
                                                        class="text-muted fst-italic">{{ $item->thuoc->hoat_chat }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark border">{{ $item->lieu_luong ?? $item->lieu_dung ?? $item->lieu ?? $item->dosage ?? '—' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-bold text-primary fs-6">{{ $item->so_luong }}</span>
                                                <span class="small text-muted">{{ $item->don_vi_tinh ?? 'viên' }}</span>
                                            </td>
                                            <td class="small text-muted">{{ $item->cach_dung }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Lời dặn --}}
                        @if ($donThuoc->loi_dan)
                            <div class="p-3 bg-warning bg-opacity-10 border-top border-warning border-opacity-25">
                                <div class="d-flex">
                                    <i class="fas fa-exclamation-circle text-warning mt-1 me-2"></i>
                                    <div>
                                        <strong class="text-warning-dark d-block">Lời dặn bác sĩ:</strong>
                                        <span class="text-dark">{{ $donThuoc->loi_dan }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Footer Action --}}
                    <div class="card-footer bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="small text-muted">
                                @if ($donThuoc->trang_thai === 'da_cap_thuoc')
                                    <i class="fas fa-calendar-check me-1"></i>Nhận thuốc ngày:
                                    <strong>{{ $donThuoc->ngay_cap_thuoc?->format('d/m/Y') }}</strong>
                                @else
                                    <i class="fas fa-map-marker-alt me-1"></i>Vui lòng nhận thuốc tại quầy dược
                                @endif
                            </div>
                            <div class="d-flex gap-2">
                                @if ($donThuoc->benhAn)
                                    <a href="{{ route('patient.benhan.show', $donThuoc->benhAn) }}"
                                        class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-file-medical me-1"></i>Xem bệnh án
                                    </a>
                                @endif
                                {{-- Nút In đơn thuốc --}}
                                <button onclick="printPrescription('{{ $donThuoc->id }}')"
                                    class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-print me-1"></i>In đơn thuốc
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Hidden Print Area (Để in đẹp hơn) --}}
                    <div id="print-area-{{ $donThuoc->id }}" class="d-none">
                        <div class="text-center mb-4">
                            <h3>ĐƠN THUỐC</h3>
                            <p>Mã đơn: #{{ $donThuoc->id }} - Ngày: {{ $donThuoc->created_at->format('d/m/Y') }}</p>
                        </div>
                        {{-- Nội dung in... (Giản lược cho ngắn gọn code) --}}
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="text-center py-5">
                    <div class="mb-3">
                        <span class="fa-stack fa-2x">
                            <i class="fas fa-circle fa-stack-2x text-light"></i>
                            <i class="fas fa-prescription-bottle-alt fa-stack-1x text-secondary opacity-50"></i>
                        </span>
                    </div>
                    <h5 class="text-muted fw-bold">Chưa có đơn thuốc nào</h5>
                    <p class="text-muted mb-4">Danh sách đơn thuốc bác sĩ kê sẽ hiển thị tại đây.</p>
                    <a href="{{ route('patient.benhan.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Xem hồ sơ bệnh án
                    </a>
                </div>
            @endforelse

            {{-- Pagination --}}
            @if ($donThuocs->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $donThuocs->links() }}
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            function printPrescription(id) {
                // Logic in đơn giản: window.print()
                // Trong thực tế có thể mở popup in riêng
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

            /* Ẩn các phần không cần thiết khi in */
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
                header {
                    display: none !important;
                }
            }
        </style>
    @endpush
@endsection
