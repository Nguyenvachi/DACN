@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-person-lines-fill text-primary me-2"></i>
                Chỉnh sửa User: {{ $user->name }}
            </h2>

            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>

        <div class="row g-4">

            {{-- =============================
             THÔNG TIN CƠ BẢN
        ============================== --}}
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">Thông tin cơ bản</h5>

                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">

                                {{-- Name --}}
                                <div class="col-md-6">
                                    <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                        class="form-control" required>
                                    @error('name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                        class="form-control" required>
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div>

                            {{-- Role Selection - Simple 4 roles --}}
                            <div class="mt-4">
                                <label class="form-label fw-semibold">Vai trò <span class="text-danger">*</span></label>
                                
                                <select name="role" class="form-select" required>
                                    @foreach($roles as $r)
                                        <option value="{{ $r }}" {{ old('role', $user->role) === $r ? 'selected' : '' }}>
                                            {{ ucfirst($r) }}
                                        </option>
                                    @endforeach
                                </select>
                                
                                <div class="mt-2 text-muted small">
                                    <ul class="mb-0">
                                        <li><strong>admin</strong>: Toàn quyền quản lý hệ thống</li>
                                        <li><strong>staff</strong>: Nhân viên (quản lý lịch hẹn, hóa đơn, bệnh án)</li>
                                        <li><strong>doctor</strong>: Bác sĩ (quản lý lịch làm việc, bệnh án)</li>
                                        <li><strong>patient</strong>: Bệnh nhân (đặt lịch, xem hồ sơ)</li>
                                    </ul>
                                </div>

                                @error('role')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Buttons --}}
                            <div class="mt-4 d-flex justify-content-end">
                                <button class="btn btn-primary me-2">
                                    <i class="bi bi-save"></i> Cập nhật
                                </button>

                                <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                                    Hủy
                                </a>
                            </div>

                        </form>

                    </div>
                </div>
            </div>

            {{-- =============================
             LỊCH SỬ ĐĂNG NHẬP
        ============================== --}}
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">10 lần đăng nhập gần nhất</h5>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Thời gian</th>
                                        <th>IP</th>
                                        <th>Trạng thái</th>
                                        <th>Lý do</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse ($loginAudits as $audit)
                                        <tr class="{{ $audit->status === 'failed' ? 'table-danger' : '' }}">
                                            <td>{{ $audit->created_at->format('d/m/Y H:i:s') }}</td>
                                            <td class="text-muted">{{ $audit->ip_address }}</td>
                                            <td>
                                                @if ($audit->status === 'success')
                                                    <span class="badge bg-success">Thành công</span>
                                                @else
                                                    <span class="badge bg-danger">Thất bại</span>
                                                @endif
                                            </td>
                                            <td class="text-muted">{{ $audit->reason ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Chưa có lịch sử đăng nhập</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection
