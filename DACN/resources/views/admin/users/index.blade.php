@extends('layouts.admin')

@section('content')

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-people-fill text-primary me-2"></i>
                Quản lý Tài khoản User
            </h2>
        </div>

        {{-- FLASH MESSAGE --}}
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('status') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- FILTER PANEL --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">

                <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">

                    {{-- Search --}}
                    <div class="col-md-4">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Tìm tên hoặc email...">
                    </div>

                    {{-- Role --}}
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">Tất cả vai trò</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                                    {{ \Illuminate\Support\Facades\Lang::has('roles.' . $role->name) ? __('roles.' . $role->name) : $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="locked" {{ request('status') === 'locked' ? 'selected' : '' }}>Bị khóa</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">
                            <i class="bi bi-funnel"></i> Lọc
                        </button>
                    </div>

                </form>

            </div>
        </div>

        {{-- MAIN TABLE --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table id="usersTable" class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:64px;">Ảnh</th>
                                <th>Tên & Email</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Đăng nhập gần nhất</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($users as $user)
                                <tr class="{{ $user->isLocked() ? 'table-danger' : '' }}">

                                    {{-- Avatar --}}
                                    <td class="align-middle text-center">
                                            @php
                                                $avatarUrl = null;
                                                if (!empty($user->avatar)) {
                                                    $avatarUrl = \Illuminate\Support\Facades\Storage::url($user->avatar);
                                                } elseif ($user->relationLoaded('bacSi') ? $user->bacSi?->avatar : ($user->bacSi ?? false)) {
                                                    // bacSi may have accessor avatar_url
                                                    $avatarUrl = $user->bacSi->avatar_url ?? ($user->bacSi->avatar ? \Illuminate\Support\Facades\Storage::url($user->bacSi->avatar) : null);
                                                } elseif ($user->relationLoaded('nhanVien') ? $user->nhanVien?->avatar_path : ($user->nhanVien ?? false)) {
                                                    $avatarUrl = $user->nhanVien->avatar_path ? \Illuminate\Support\Facades\Storage::url($user->nhanVien->avatar_path) : null;
                                                } elseif ($user->relationLoaded('patientProfile') ? $user->patientProfile?->avatar : ($user->patientProfile ?? false)) {
                                                    $avatarUrl = $user->patientProfile->avatar ? \Illuminate\Support\Facades\Storage::url($user->patientProfile->avatar) : null;
                                                }
                                            @endphp

                                            @if ($avatarUrl)
                                                <img src="{{ $avatarUrl }}" alt="{{ $user->name }}" class="rounded-circle" style="width:40px;height:40px;object-fit:cover;">
                                            @else
                                                <div class="rounded-circle bg-light text-muted d-inline-flex align-items-center justify-content-center" style="width:40px;height:40px;">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                            @endif
                                    </td>

                                    {{-- Name + Email --}}
                                    <td>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <div class="text-muted small">{{ $user->email }}</div>
                                    </td>

                                    {{-- Roles --}}
                                    <td>
                                        @forelse ($user->roles as $role)
                                            <span
                                                class="badge bg-primary">{{ \Illuminate\Support\Facades\Lang::has('roles.' . $role->name) ? __('roles.' . $role->name) : $role->name }}</span>
                                        @empty
                                            <span class="text-muted">Chưa gán</span>
                                        @endforelse
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        @if ($user->isLocked())
                                            <span class="badge bg-danger">Bị khóa</span>
                                        @else
                                            <span class="badge bg-success">Hoạt động</span>
                                        @endif

                                        @if ($user->must_change_password)
                                            <span class="badge bg-warning text-dark">Đổi MK</span>
                                        @endif
                                    </td>

                                    {{-- Last Login --}}
                                    <td class="text-muted">
                                        {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Chưa đăng nhập' }}
                                    </td>

                                    {{-- Actions --}}
                                    <td class="text-center">

                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="btn btn-sm btn-outline-primary me-1" title="Sửa">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        {{-- Lock / Unlock --}}
                                        @if ($user->isLocked())
                                            <form method="POST" action="{{ route('admin.users.unlock', $user) }}"
                                                class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-success me-1" title="Mở khóa">
                                                    <i class="bi bi-unlock"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.lock', $user) }}"
                                                class="d-inline">
                                                @csrf
                                                <button onclick="return confirm('Xác nhận khóa tài khoản này?')"
                                                    class="btn btn-sm btn-outline-danger me-1" title="Khóa">
                                                    <i class="bi bi-lock"></i>
                                                </button>
                                            </form>
                                        @endif

                                        {{-- Force password change --}}
                                        <form method="POST"
                                            action="{{ route('admin.users.force-password-change', $user) }}"
                                            class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-warning">
                                                Bắt đổi MK
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        Không tìm thấy user nào
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

            </div>

            {{-- FOOTER: Pagination - Removed, using DataTables pagination --}}
        </div>

    </div>

@endsection

{{-- DataTables Script --}}
<x-datatable-script tableId="usersTable" />
