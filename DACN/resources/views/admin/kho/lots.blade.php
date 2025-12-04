@extends('layouts.admin')

@section('content')
    <style>
        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
            border: 1px solid #eee;
        }

        .stat-label {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-box-seam me-2"></i>
                Các lô của thuốc: {{ $thuoc->ten }}
            </h2>

            <div>
                <a href="{{ route('admin.kho.index') }}" class="btn btn-secondary btn-sm me-1">
                    <i class="bi bi-arrow-left"></i> Quay lại kho
                </a>

                <a href="{{ route('admin.kho.nhap.form') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Nhập thêm
                </a>
            </div>
        </div>

        {{-- STAT CARDS --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-collection text-primary fs-4 me-2"></i>
                        <div class="stat-label">Tổng số lượng</div>
                    </div>
                    <div class="stat-value">{{ number_format($tong_sl) }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-cash-stack text-success fs-4 me-2"></i>
                        <div class="stat-label">Tổng giá trị nhập</div>
                    </div>
                    <div class="stat-value">
                        {{ number_format($tong_val, 0, ',', '.') }} đ
                    </div>
                </div>
            </div>

        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:14%">Mã lô</th>
                                <th style="width:14%">Hạn sử dụng</th>
                                <th style="width:10%">Số lượng</th>
                                <th style="width:14%">Giá nhập</th>
                                <th style="width:14%">Giá xuất</th>
                                <th style="width:14%">Giá trị tồn (nhập)</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($lots as $l)
                                <tr>
                                    <td>{{ $l->ma_lo }}</td>
                                    <td>{{ $l->han_su_dung }}</td>
                                    <td>{{ number_format($l->so_luong) }}</td>
                                    <td>{{ number_format($l->gia_nhap, 0, ',', '.') }} đ</td>
                                    <td>{{ number_format($l->gia_xuat, 0, ',', '.') }} đ</td>
                                    <td>{{ number_format($l->so_luong * $l->gia_nhap, 0, ',', '.') }} đ</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="bi bi-info-circle me-1"></i> Chưa có lô nào
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection
