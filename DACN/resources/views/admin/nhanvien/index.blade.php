@extends('layouts.admin')

@section('content')

    <style>
        .card-custom {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        /* Force button sizing */
        .action-btn {
            width: 36px;
            height: 36px;
            padding: 0 !important;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border-radius: 6px !important;
        }

        /* Để td không ép nút bị co lại */
        td.text-nowrap {
            white-space: nowrap !important;
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-people-fill me-2 text-primary"></i> Nhân viên
            </h2>

            <div>
                <a class="btn btn-success btn-sm me-2" href="{{ route('admin.nhanvien.shifts.export') }}">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Xuất ca làm việc
                </a>

                <a class="btn btn-primary btn-sm" href="{{ route('admin.nhanvien.create') }}">
                    <i class="bi bi-plus-lg"></i> Thêm nhân viên
                </a>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card card-custom">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th>Họ tên</th>
                                <th>Chức vụ</th>
                                <th>Email công việc</th>
                                <th style="width: 160px;">Trạng thái</th>
                                <th style="width: 140px;">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>

                            @php
                                // Đồng bộ trạng thái toàn hệ thống
                                $statusLabels = [
                                    'active' => 'Đang làm',
                                    'inactive' => 'Ngừng hoạt động',
                                ];
                                // Màu cho từng trạng thái (có thể mở rộng)
                                $statusColors = [
                                    'active' => 'success',
                                    'inactive' => 'secondary',
                                ];
                            @endphp

                            {{-- Flash messages --}}
                            @if (session('status'))
                                <tr>
                                    <td colspan="6">
                                        <div class="alert alert-success mb-0">{{ session('status') }}</div>
                                    </td>
                                </tr>
                            @endif

                            @forelse($items as $nv)
                                <tr>
                                    <td class="fw-semibold">{{ $nv->id }}</td>

                                    <td>{{ $nv->ho_ten }}</td>

                                    <td>{{ $nv->chuc_vu }}</td>

                                    <td>{{ $nv->email_cong_viec }}</td>

                                    {{-- TRẠNG THÁI --}}
                                    <td style="vertical-align: middle;">
                                        <div class="d-flex align-items-center">
                                            {{-- Hiện badge trạng thái hiện tại --}}
                                            @php
                                                $current = $nv->trang_thai ?? '';
                                                $badgeColor = $statusColors[$current] ?? 'secondary';
                                            @endphp
                                            <span
                                                class="badge bg-{{ $badgeColor }} me-2">{{ $statusLabels[$current] ?? $current }}</span>

                                            {{-- Form chọn nhanh để thay đổi trạng thái --}}
                                            <form method="POST" action="{{ route('admin.nhanvien.updateStatus', $nv) }}">
                                                @csrf
                                                @method('PATCH')

                                                <select name="trang_thai" class="form-select form-select-sm"
                                                    style="width:160px; display:inline-block;"
                                                    onchange="this.form.submit()">

                                                    @foreach ($statusLabels as $value => $label)
                                                        <option value="{{ $value }}" @selected($nv->trang_thai == $value)>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach

                                                </select>
                                            </form>
                                        </div>
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="text-nowrap">

                                        <a href="{{ route('admin.nhanvien.show', $nv) }}"
                                            class="btn btn-sm btn-outline-secondary me-1"
                                            title="Xem">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <a href="{{ route('admin.nhanvien.edit', $nv) }}"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                    </td>

                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                                        Chưa có nhân viên nào
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>
                </div>

            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="mt-3">
            {{ $items->links() }}
        </div>

    </div>

@endsection
