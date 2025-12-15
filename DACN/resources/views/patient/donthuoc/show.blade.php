@extends('layouts.patient-modern')

@section('title', 'Chi tiết đơn thuốc #' . str_pad($donThuoc->id, 5, '0', STR_PAD_LEFT))
@section('page-title', 'Chi tiết đơn thuốc')
@section('page-subtitle', 'Thông tin thuốc và hướng dẫn sử dụng chi tiết')

@section('content')
    <div class="row g-4">
        {{-- CỘT TRÁI: NỘI DUNG ĐƠN THUỐC (8 phần) --}}
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4" id="print-section">
                {{-- Header Card --}}
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">
                            <i class="fas fa-file-prescription me-2"></i>Đơn thuốc
                            #{{ str_pad($donThuoc->id, 5, '0', STR_PAD_LEFT) }}
                        </h5>
                        {{-- Badge Trạng thái --}}
                        @if ($donThuoc->trang_thai === 'da_cap_thuoc')
                            <span
                                class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                Đã cấp thuốc
                            </span>
                        @else
                            <span
                                class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                Chờ cấp thuốc
                            </span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    {{-- Thông tin chung --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <small class="text-muted text-uppercase fw-bold">Ngày kê đơn</small>
                            <div class="fs-5 text-dark fw-bold">
                                {{ $donThuoc->created_at->format('d/m/Y') }}
                                <small class="text-muted fw-normal">({{ $donThuoc->created_at->format('H:i') }})</small>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <small class="text-muted text-uppercase fw-bold">Bác sĩ kê đơn</small>
                            <div class="fs-5 text-dark fw-bold">
                                {{ $donThuoc->benhAn->bacSi->ho_ten ?? ($donThuoc->bacSi->user->name ?? 'N/A') }}
                            </div>
                        </div>
                    </div>

                    {{-- Block Thông tin Bệnh án liên quan --}}
                    @if ($donThuoc->benhAn)
                        <div class="bg-light p-3 rounded mb-4 border border-dashed">
                            <div class="row g-2">
                                <div class="col-md-12">
                                    <span class="text-muted small"><i class="fas fa-stethoscope me-1"></i>Chẩn đoán:</span>
                                    <span
                                        class="fw-bold text-danger">{{ $donThuoc->benhAn->chan_doan ?? 'Chưa cập nhật' }}</span>
                                </div>
                                <div class="col-md-12">
                                    <span class="text-muted small"><i class="fas fa-link me-1"></i>Từ bệnh án:</span>
                                    <a href="{{ route('patient.benhan.show', $donThuoc->benhAn) }}"
                                        class="text-decoration-none fw-bold">
                                        #{{ $donThuoc->benh_an_id }} - Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Danh sách thuốc --}}
                    <h6 class="mb-3 fw-bold text-secondary text-uppercase small">
                        <i class="fas fa-pills me-2"></i>Danh sách thuốc & hướng dẫn
                    </h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="bg-light text-secondary small text-uppercase">
                                <tr>
                                    <th style="width: 5%" class="text-center">#</th>
                                    <th style="width: 35%">Tên thuốc / Hoạt chất</th>
                                    <th style="width: 15%" class="text-center">Số lượng</th>
                                    <th style="width: 45%">Cách dùng / Liều lượng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($donThuoc->items as $index => $item)
                                    <tr>
                                        <td class="text-center text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $item->thuoc->ten_thuoc ?? $item->ten_thuoc }}
                                            </div>
                                            @if (optional($item->thuoc)->hoat_chat)
                                                <small class="text-muted fst-italic">{{ $item->thuoc->hoat_chat }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary fs-6">{{ $item->so_luong }}</span>
                                            <div class="small text-muted">{{ $item->don_vi_tinh ?? 'viên' }}</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold text-dark">
                                                {{ $item->lieu_luong ?? $item->lieu_dung ?? $item->lieu ?? $item->dosage ?? '—' }}
                                            </div>
                                            <div class="small text-muted">{{ $item->cach_dung ?? '—' }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Lời dặn --}}
                    @if ($donThuoc->loi_dan)
                        <div class="alert alert-warning border-warning d-flex align-items-start" role="alert">
                            <i class="fas fa-exclamation-circle fa-lg me-3 mt-1"></i>
                            <div>
                                <h6 class="alert-heading fw-bold mb-1">Lời dặn của bác sĩ:</h6>
                                <p class="mb-0">{{ $donThuoc->loi_dan }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="card-footer bg-light text-center text-muted small py-3 d-none d-print-block">
                    Phiếu này được in từ hệ thống Healthcare Clinic. Ngày in: {{ now()->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>

        {{-- CỘT PHẢI: THÔNG TIN & TÁC VỤ (4 phần) --}}
        <div class="col-lg-4">
            {{-- Card Thông tin bệnh nhân --}}
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-user-circle me-2"></i>Thông tin bệnh nhân</h6>
                </div>
                <div class="card-body">
                    @php $user = auth()->user(); @endphp
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-3 me-3 fw-bold">
                            {{ strtoupper(substr($user->name ?? 'BN', 0, 1)) }}
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $user->name }}</h6>
                            <small class="text-muted">{{ $user->email }}</small>
                        </div>
                    </div>
                    <hr class="my-3">
                    <p class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Mã số:</span>
                        <span class="fw-bold">#{{ $user->id }}</span>
                    </p>
                    <p class="mb-0 d-flex justify-content-between">
                        <span class="text-muted">Ngày sinh:</span>
                        <span class="fw-bold">
                            {{ $user->ngay_sinh ? \Carbon\Carbon::parse($user->ngay_sinh)->format('d/m/Y') : '---' }}
                        </span>
                    </p>
                </div>
            </div>

            {{-- Card Tác vụ --}}
            <div class="card shadow-sm sticky-top" style="top: 20px; z-index: 1;">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-tasks me-2"></i>Tác vụ</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    <button onclick="window.print()" class="btn btn-primary">
                        <i class="fas fa-print me-2"></i>In đơn thuốc
                    </button>

                    @if ($donThuoc->benhAn)
                        <a href="{{ route('patient.benhan.show', $donThuoc->benhAn) }}" class="btn btn-outline-info">
                            <i class="fas fa-file-medical-alt me-2"></i>Xem bệnh án gốc
                        </a>
                    @endif

                    <a href="{{ route('patient.donthuoc.index') }}" class="btn btn-link text-decoration-none text-muted">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                    </a>
                </div>
            </div>

            {{-- Card Lưu ý (Giữ lại từ file cũ của bạn nhưng style đẹp hơn) --}}
            <div class="card shadow-sm mt-4 border-info">
                <div class="card-header bg-info text-white py-2">
                    <h6 class="mb-0 small fw-bold text-uppercase"><i class="fas fa-lightbulb me-2"></i>Lưu ý khi dùng thuốc
                    </h6>
                </div>
                <div class="card-body bg-info bg-opacity-10">
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Uống đúng liều lượng bác sĩ kê.
                        </li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Tuân thủ thời gian uống thuốc.</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Bảo quản thuốc nơi khô ráo.</li>
                        <li><i class="fas fa-phone text-primary me-2"></i>Liên hệ bác sĩ nếu có tác dụng phụ.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            @media print {
                body * {
                    visibility: hidden;
                }

                #print-section,
                #print-section * {
                    visibility: visible;
                }

                #print-section {
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
                .sticky-top {
                    display: none !important;
                }
            }
        </style>
    @endpush
@endsection
