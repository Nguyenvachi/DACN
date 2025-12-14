@extends('layouts.admin')

@section('content')

    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-weight: 700;
            font-size: 28px;
        }

        .card-custom {
            border-radius: 12px;
            border: 0;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.06);
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="page-header mb-4">
            <h1 class="page-title">
                <i class="bi bi-calendar-check-fill me-2 text-primary"></i>
                Quản lý Lịch hẹn
            </h1>
        </div>

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- MAIN CARD --}}
        <div class="card card-custom">
            <div class="card-body">

                {{-- BỘ LỌC --}}
                <form method="GET" action="{{ route('admin.lichhen.index') }}" class="mb-4">
                    <div class="row g-3">
                        {{-- Lọc theo Bác sĩ --}}
                        <div class="col-md-3">
                            <label class="form-label"><i class="bi bi-person-badge"></i> Bác sĩ</label>
                            <select name="bac_si_id" class="form-select">
                                <option value="">-- Tất cả bác sĩ --</option>
                                @foreach(\App\Models\BacSi::orderBy('ho_ten')->get() as $bs)
                                    <option value="{{ $bs->id }}" {{ request('bac_si_id') == $bs->id ? 'selected' : '' }}>
                                        {{ $bs->ho_ten }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Lọc theo Phòng --}}
                        <div class="col-md-3">
                            <label class="form-label"><i class="bi bi-door-closed"></i> Phòng khám</label>
                            <select name="phong_id" class="form-select">
                                <option value="">-- Tất cả phòng --</option>
                                @foreach(\App\Models\Phong::orderBy('ten')->get() as $phong)
                                    <option value="{{ $phong->id }}" {{ request('phong_id') == $phong->id ? 'selected' : '' }}>
                                        {{ $phong->ten }} @if($phong->vi_tri)({{ $phong->vi_tri }})@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Lọc theo Trạng thái --}}
                        <div class="col-md-3">
                            <label class="form-label"><i class="bi bi-info-circle"></i> Trạng thái</label>
                            <select name="trang_thai" class="form-select">
                                <option value="">-- Tất cả trạng thái --</option>
                                <option value="{{ \App\Models\LichHen::STATUS_PENDING_VN }}" {{ request('trang_thai') == \App\Models\LichHen::STATUS_PENDING_VN ? 'selected' : '' }}>{{ \App\Models\LichHen::STATUS_PENDING_VN }}</option>
                                <option value="{{ \App\Models\LichHen::STATUS_CONFIRMED_VN }}" {{ request('trang_thai') == \App\Models\LichHen::STATUS_CONFIRMED_VN ? 'selected' : '' }}>{{ \App\Models\LichHen::STATUS_CONFIRMED_VN }}</option>
                                <option value="{{ \App\Models\LichHen::STATUS_CANCELLED_VN }}" {{ request('trang_thai') == \App\Models\LichHen::STATUS_CANCELLED_VN ? 'selected' : '' }}>{{ \App\Models\LichHen::STATUS_CANCELLED_VN }}</option>
                                <option value="{{ \App\Models\LichHen::STATUS_COMPLETED_VN }}" {{ request('trang_thai') == \App\Models\LichHen::STATUS_COMPLETED_VN ? 'selected' : '' }}>{{ \App\Models\LichHen::STATUS_COMPLETED_VN }}</option>
                            </select>
                        </div>

                        {{-- Nút Lọc --}}
                        <div class="col-md-3">
                            <label class="form-label d-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-funnel"></i> Lọc
                            </button>
                            @if(request()->hasAny(['bac_si_id', 'phong_id', 'trang_thai']))
                                <a href="{{ route('admin.lichhen.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                                    <i class="bi bi-x-circle"></i> Xóa bộ lọc
                                </a>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="lichhenTable" class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Bệnh nhân</th>
                                <th>Bác sĩ</th>
                                <th>Phòng</th>
                                <th>Dịch vụ</th>
                                <th>Ngày hẹn</th>
                                <th>Giờ</th>
                                <th>Trạng thái</th>
                                <th>Hóa đơn</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($danhSachLichHen as $lichHen)
                                <tr>
                                    <td class="fw-bold">{{ $lichHen->id }}</td>

                                    {{-- Bệnh nhân --}}
                                    <td>
                                        <strong>{{ $lichHen->user->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $lichHen->user->email ?? '' }}</small>
                                    </td>

                                    {{-- Bác sĩ --}}
                                    <td>{{ $lichHen->bacSi->ho_ten ?? 'N/A' }}</td>

                                    {{-- Phòng --}}
                                    <td>
                                        @php
                                            $phong = $lichHen->bacSi?->lichLamViecs
                                                ->where('ngay_trong_tuan', \Carbon\Carbon::parse($lichHen->ngay_hen)->dayOfWeek)
                                                ->first()?->phong;
                                        @endphp
                                        @if($phong)
                                            <span class="badge bg-info">
                                                <i class="bi bi-door-closed"></i> {{ $phong->ten }}
                                            </span>
                                            @if($phong->vi_tri)
                                                <br><small class="text-muted">{{ $phong->vi_tri }}</small>
                                            @endif
                                        @else
                                            <span class="text-muted">--</span>
                                        @endif
                                    </td>

                                    {{-- Dịch vụ --}}
                                    <td>
                                        {{ $lichHen->dichVu->ten_dich_vu ?? 'N/A' }}<br>
                                        <small class="text-muted">
                                            {{ number_format($lichHen->dichVu->gia ?? 0) }} VNĐ
                                        </small>
                                    </td>

                                    {{-- Ngày - Giờ --}}
                                    <td>{{ \Carbon\Carbon::parse($lichHen->ngay_hen)->format('d/m/Y') }}</td>
                                    <td>{{ $lichHen->thoi_gian_hen }}</td>

                                    {{-- Trạng thái --}}
                                    <td>
                                        <form action="{{ route('admin.lichhen.updateStatus', $lichHen) }}" method="POST">
                                            @csrf
                                            @method('PATCH')

                                            <select name="trang_thai" class="form-select form-select-sm"
                                                onchange="this.form.submit()">

                                                <option value="{{ \App\Models\LichHen::STATUS_PENDING_VN }}" class="text-warning fw-bold"
                                                    @selected($lichHen->trang_thai == \App\Models\LichHen::STATUS_PENDING_VN)>
                                                    {{ \App\Models\LichHen::STATUS_PENDING_VN }}
                                                </option>

                                                <option value="{{ \App\Models\LichHen::STATUS_CONFIRMED_VN }}" class="text-primary fw-bold"
                                                    @selected($lichHen->trang_thai == \App\Models\LichHen::STATUS_CONFIRMED_VN)>
                                                    {{ \App\Models\LichHen::STATUS_CONFIRMED_VN }}
                                                </option>

                                                <option value="{{ \App\Models\LichHen::STATUS_CANCELLED_VN }}" class="text-danger fw-bold"
                                                    @selected($lichHen->trang_thai == \App\Models\LichHen::STATUS_CANCELLED_VN)>
                                                    {{ \App\Models\LichHen::STATUS_CANCELLED_VN }}
                                                </option>

                                                <option value="{{ \App\Models\LichHen::STATUS_COMPLETED_VN }}" class="text-success fw-bold"
                                                    @selected($lichHen->trang_thai == \App\Models\LichHen::STATUS_COMPLETED_VN)>
                                                    {{ \App\Models\LichHen::STATUS_COMPLETED_VN }}
                                                </option>

                                            </select>
                                        </form>
                                    </td>

                                    {{-- Hóa đơn --}}
                                    <td>
                                        @if ($lichHen->is_paid)
                                            <span class="badge bg-success mb-2">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Đã thanh toán
                                            </span><br>

                                            @if ($lichHen->hoaDon)
                                                <a href="{{ route('admin.hoadon.show', $lichHen->hoaDon->id) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="bi bi-receipt-cutoff"></i> Xem HĐ
                                                </a>
                                            @endif
                                        @else
                                            <span class="badge bg-warning mb-2">
                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                Chưa thanh toán
                                            </span>
                                        @endif
                                    </td>

                                </tr>
                            @empty

                                {{-- EMPTY --}}
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="bi bi-calendar-x fs-1 d-block mb-2"></i>
                                        <p class="mb-0">Chưa có lịch hẹn nào.</p>
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
<x-datatable-script tableId="lichhenTable" />
