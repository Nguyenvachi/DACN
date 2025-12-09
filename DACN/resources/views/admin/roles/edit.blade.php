@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="fas fa-user-shield me-2"></i>
                Chỉnh sửa vai trò: {{ $role->name }}
            </h2>

            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại
            </a>
        </div>

        @php
            $rolePermissionIds = $role->permissions->pluck('id')->toArray();
        @endphp

        {{-- MAIN CARD --}}
        <div class="card shadow-lg border-0">
            <div class="card-body">

                <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                    @csrf
                    @method('PUT')

                    {{-- Tên vai trò --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Tên vai trò <span class="text-danger">*</span>
                        </label>

                        <input type="text" name="name" value="{{ old('name', $role->name) }}"
                            class="form-control form-control-lg" required>

                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Gán quyền --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            Gán quyền cho vai trò này
                        </label>

                        <div class="border rounded p-3" style="max-height: 340px; overflow-y: auto;">

                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-2">

                                @forelse ($permissions as $permission)
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="perm_{{ $permission->id }}"
                                                name="permissions[]" value="{{ $permission->id }}"
                                                {{ in_array($permission->id, old('permissions', $rolePermissionIds)) ? 'checked' : '' }}>

                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                @php
                                                    $pname = $permission->name;
                                                    $candidates = [
                                                        'permissions.' . $pname,
                                                        'permissions.' . strtolower($pname),
                                                        'permissions.' . str_replace(' ', '-', strtolower($pname)),
                                                        'permissions.' . str_replace([' ', '-'], '_', strtolower($pname)),
                                                    ];
                                                    $label = null;
                                                    foreach ($candidates as $k) {
                                                        if (\Illuminate\Support\Facades\Lang::has($k)) {
                                                            $label = __($k);
                                                            break;
                                                        }
                                                    }
                                                    $label = $label ?: ucwords(str_replace(['-','_'], ' ', $pname));
                                                @endphp
                                                {{ $label }}
                                            </label>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">Chưa có quyền nào.</p>
                                @endforelse

                            </div>

                        </div>

                        @error('permissions')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- BUTTONS --}}
                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-light me-2">
                            <i class="fas fa-times"></i> Hủy
                        </a>

                        <button class="btn btn-primary px-4">
                            <i class="fas fa-save me-1"></i> Cập nhật vai trò
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
@endsection
