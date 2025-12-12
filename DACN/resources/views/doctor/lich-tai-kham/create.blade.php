@extends('layouts.doctor')

@section('title', 'Tạo lịch tái khám')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-calendar-check"></i> Tạo lịch tái khám</h2>
        <a href="{{ $benhAn ? route('doctor.benhan.show', $benhAn->id) : route('doctor.lich-tai-kham.index') }}" 
           class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if ($benhAn)
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"><i class="fas fa-user"></i> Thông tin bệnh nhân</h5>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Họ tên:</strong> {{ $benhAn->user->name }}</p>
                    <p><strong>Ngày sinh:</strong> {{ \Carbon\Carbon::parse($benhAn->user->ngay_sinh)->format('d/m/Y') }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Số điện thoại:</strong> {{ $benhAn->user->so_dien_thoai }}</p>
                    <p><strong>Email:</strong> {{ $benhAn->user->email }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('doctor.lich-tai-kham.store') }}" method="POST">
                @csrf

                @if ($benhAn)
                    <input type="hidden" name="benh_an_id" value="{{ $benhAn->id }}">
                @else
                    <div class="mb-3">
                        <label for="benh_an_id" class="form-label">
                            Mã bệnh án <span class="text-danger">*</span>
                        </label>
                        <input type="number" 
                               class="form-control @error('benh_an_id') is-invalid @enderror" 
                               id="benh_an_id" 
                               name="benh_an_id" 
                               value="{{ old('benh_an_id') }}" 
                               required>
                        @error('benh_an_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="ngay_hen" class="form-label">
                            Ngày hẹn tái khám <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               class="form-control @error('ngay_hen') is-invalid @enderror" 
                               id="ngay_hen" 
                               name="ngay_hen" 
                               value="{{ old('ngay_hen') }}" 
                               min="{{ date('Y-m-d') }}" 
                               required>
                        @error('ngay_hen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="gio_hen" class="form-label">Giờ hẹn</label>
                        <input type="time" 
                               class="form-control @error('gio_hen') is-invalid @enderror" 
                               id="gio_hen" 
                               name="gio_hen" 
                               value="{{ old('gio_hen', '09:00') }}">
                        @error('gio_hen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Để trống nếu chưa xác định giờ</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="ly_do" class="form-label">
                        Lý do tái khám <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control @error('ly_do') is-invalid @enderror" 
                              id="ly_do" 
                              name="ly_do" 
                              rows="3" 
                              required>{{ old('ly_do') }}</textarea>
                    @error('ly_do')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Ví dụ: Kiểm tra lại kết quả xét nghiệm, theo dõi sức khỏe thai nhi...</div>
                </div>

                <div class="mb-3">
                    <label for="luu_y" class="form-label">Lưu ý cho bệnh nhân</label>
                    <textarea class="form-control @error('luu_y') is-invalid @enderror" 
                              id="luu_y" 
                              name="luu_y" 
                              rows="3">{{ old('luu_y') }}</textarea>
                    @error('luu_y')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Ví dụ: Nhịn ăn 8 tiếng trước khám, mang theo kết quả xét nghiệm cũ...</div>
                </div>

                <div class="mb-3">
                    <label for="ghi_chu" class="form-label">Ghi chú</label>
                    <textarea class="form-control @error('ghi_chu') is-invalid @enderror" 
                              id="ghi_chu" 
                              name="ghi_chu" 
                              rows="2">{{ old('ghi_chu') }}</textarea>
                    @error('ghi_chu')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ $benhAn ? route('doctor.benhan.show', $benhAn->id) : route('doctor.lich-tai-kham.index') }}" 
                       class="btn btn-secondary">
                        Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Tạo lịch tái khám
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-label {
        font-weight: 500;
    }
    .card {
        border: none;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
</style>
@endpush
@endsection
