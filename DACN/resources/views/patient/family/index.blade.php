@extends('layouts.patient-modern')

@section('title', 'Quản lý thành viên gia đình')
@section('page-title', 'Thành viên gia đình')
@section('page-subtitle', 'Quản lý thông tin thành viên gia đình để đặt lịch khám')

@section('content')
<div class="row g-4">
    {{-- Thêm thành viên mới --}}
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1"><i class="fas fa-users me-2"></i>Danh sách thành viên</h6>
                        <p class="text-muted mb-0">Quản lý thông tin thành viên để đặt lịch khám nhanh hơn</p>
                    </div>
                    <a href="{{ route('patient.family.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Thêm thành viên
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Danh sách thành viên --}}
    <div class="col-12">
        <div class="row g-4">
            {{-- Bản thân --}}
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    @if(auth()->user()->avatar)
                                        <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="Avatar" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <i class="fas fa-user text-white fs-3"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ auth()->user()->name }}</h6>
                                <span class="badge bg-primary">Bản thân</span>
                            </div>
                        </div>

                        <div class="mb-2">
                            <p class="text-muted mb-1"><i class="fas fa-birthday-cake me-2"></i>Ngày sinh:</p>
                            <p class="mb-0">{{ auth()->user()->ngay_sinh ? \Carbon\Carbon::parse(auth()->user()->ngay_sinh)->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                        </div>

                        <div class="mb-2">
                            <p class="text-muted mb-1"><i class="fas fa-venus-mars me-2"></i>Giới tính:</p>
                            <p class="mb-0">{{ auth()->user()->gioi_tinh === 'nam' ? 'Nam' : (auth()->user()->gioi_tinh === 'nu' ? 'Nữ' : 'Chưa cập nhật') }}</p>
                        </div>

                        <div class="mb-2">
                            <p class="text-muted mb-1"><i class="fas fa-phone me-2"></i>Số điện thoại:</p>
                            <p class="mb-0">{{ auth()->user()->so_dien_thoai ?? 'Chưa cập nhật' }}</p>
                        </div>

                        <div class="mt-3 d-grid">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit me-1"></i>Cập nhật thông tin
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Các thành viên khác --}}
            @forelse($familyMembers as $member)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        @if($member->avatar)
                                            <img src="{{ Storage::url($member->avatar) }}" alt="Avatar" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <i class="fas fa-user text-muted fs-3"></i>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $member->ho_ten }}</h6>
                                    <span class="badge bg-secondary">{{ ucfirst($member->quan_he) }}</span>
                                </div>
                            </div>

                            <div class="mb-2">
                                <p class="text-muted mb-1"><i class="fas fa-birthday-cake me-2"></i>Ngày sinh:</p>
                                <p class="mb-0">{{ $member->ngay_sinh ? \Carbon\Carbon::parse($member->ngay_sinh)->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                            </div>

                            <div class="mb-2">
                                <p class="text-muted mb-1"><i class="fas fa-venus-mars me-2"></i>Giới tính:</p>
                                <p class="mb-0">{{ $member->gioi_tinh === 'nam' ? 'Nam' : ($member->gioi_tinh === 'nu' ? 'Nữ' : 'Khác') }}</p>
                            </div>

                            <div class="mb-2">
                                <p class="text-muted mb-1"><i class="fas fa-phone me-2"></i>Số điện thoại:</p>
                                <p class="mb-0">{{ $member->so_dien_thoai ?? 'Chưa cập nhật' }}</p>
                            </div>

                            @if($member->bhyt_ma_so)
                                <div class="mb-2">
                                    <p class="text-muted mb-1"><i class="fas fa-id-card me-2"></i>Mã BHYT:</p>
                                    <p class="mb-0">{{ $member->bhyt_ma_so }}</p>
                                </div>
                            @endif

                            <div class="mt-3 d-flex gap-2">
                                <a href="{{ route('patient.family.edit', $member) }}" class="btn btn-outline-primary btn-sm flex-grow-1">
                                    <i class="fas fa-edit me-1"></i>Sửa
                                </a>
                                <form action="{{ route('patient.family.destroy', $member) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa thành viên này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-users fs-1 text-muted d-block mb-3"></i>
                            <h5 class="text-muted">Chưa có thành viên nào</h5>
                            <p class="text-muted">Thêm thành viên gia đình để đặt lịch khám cho họ nhanh hơn</p>
                            <a href="{{ route('patient.family.create') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-plus me-2"></i>Thêm thành viên đầu tiên
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
