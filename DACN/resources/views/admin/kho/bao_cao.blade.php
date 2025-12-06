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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-bar-chart-fill me-2"></i>
                Báo cáo tổng quan kho thuốc
            </h2>

            <a href="{{ route('admin.kho.index') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>

        {{-- STAT CARDS --}}
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-box-seam text-primary fs-4 me-2"></i>
                        <div class="stat-label">Tổng số lượng tồn</div>
                    </div>
                    <div class="stat-value">{{ number_format($val->tong_sl) }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-currency-dollar text-success fs-4 me-2"></i>
                        <div class="stat-label">Giá trị theo giá nhập</div>
                    </div>
                    <div class="stat-value">
                        {{ number_format($val->gia_tri_nhap, 0, ',', '.') }} đ
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-ticket-detailed text-warning fs-4 me-2"></i>
                        <div class="stat-label">Giá trị theo giá xuất</div>
                    </div>
                    <div class="stat-value">
                        {{ number_format($val->gia_tri_xuat, 0, ',', '.') }} đ
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-graph-up-arrow text-danger fs-4 me-2"></i>
                        <div class="stat-label">COGS 30 ngày gần nhất</div>
                    </div>
                    <div class="stat-value">
                        {{ number_format($recentCogs, 0, ',', '.') }} đ
                    </div>
                </div>
            </div>

        </div>

        {{-- TOP ITEMS --}}
        <h5 class="mt-4 mb-3 fw-bold">
            <i class="bi bi-capsule-pill me-2"></i>
            Top 10 thuốc tồn nhiều nhất
        </h5>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="baoCaoTable" class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Thuốc</th>
                                <th style="width:120px;">Tồn</th>
                                <th style="width:120px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topThuocs as $t)
                                <tr>
                                    <td>{{ $t->thuoc->ten ?? '#' . $t->thuoc_id }}</td>
                                    <td>{{ number_format($t->ton) }}</td>
                                    <td>
                                        <a href="{{ route('admin.kho.lots', $t->thuoc_id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Xem lô
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- BACK --}}
        <div class="mt-4">
            <a href="{{ route('admin.kho.index') }}" class="btn btn-light">
                &laquo; Quay lại kho
            </a>
        </div>

    </div>
@endsection

{{-- DataTables Script --}}
<x-datatable-script tableId="baoCaoTable" />

{{-- DataTables Script --}}
<x-datatable-script tableId="baoCaoTable" />
