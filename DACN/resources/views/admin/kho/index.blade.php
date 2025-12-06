@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- ============================
         🔥 HEADER
    ============================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-box-seam me-2"></i>Kho thuốc – Tồn kho
            </h2>

            <div>
                <a href="{{ route('admin.kho.nhap.form') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-download me-1"></i> Nhập kho
                </a>
                <a href="{{ route('admin.kho.xuat.form') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-upload me-1"></i> Xuất kho
                </a>
            </div>
        </div>


        {{-- ============================
         🔥 TABLE KHO THUỐC
    ============================= --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="fw-semibold mb-0">
                    <i class="bi bi-clipboard-data text-primary me-2"></i>
                    Danh sách tồn kho
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table id="khoTable" class="table table-striped table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th width="60">#</th>
                                <th width="35%">Tên thuốc</th>
                                <th width="20%">Đơn vị</th>
                                <th width="15%">Tồn kho</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($thuocs as $i => $t)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-semibold">{{ $t->ten }}</td>
                                    <td>{{ $t->don_vi }}</td>
                                    <td>
                                        @php $stock = (int)($t->ton_kho ?? 0); @endphp

                                        @if ($stock > 50)
                                            <span class="badge bg-success">{{ $stock }}</span>
                                        @elseif($stock > 10)
                                            <span class="badge bg-warning text-dark">{{ $stock }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ $stock }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="bi bi-inboxes fs-3 d-block mb-2"></i>
                                        Chưa có dữ liệu tồn kho
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

{{-- DataTables Script --}}
<x-datatable-script tableId="khoTable" />
