@extends('layouts.patient-modern')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-star-fill text-warning me-2"></i>
            Đánh giá bác sĩ
        </h2>
        <a href="{{ route('lichhen.my') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Thông tin lịch hẹn</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <strong>Bác sĩ:</strong> {{ $lichHen->bacSi->ho_ten }}
                        </div>
                        <div class="col-md-6">
                            <strong>Dịch vụ:</strong> {{ $lichHen->dichVu->ten_dich_vu }}
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày hẹn:</strong> {{ \Carbon\Carbon::parse($lichHen->ngay_hen)->format('d/m/Y') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Thời gian:</strong> {{ $lichHen->thoi_gian_hen }}
                        </div>
                    </div>

                    <hr>

                    <form action="{{ route('patient.danhgia.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="lich_hen_id" value="{{ $lichHen->id }}">

                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                Đánh giá chất lượng dịch vụ <span class="text-danger">*</span>
                            </label>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5" {{ old('rating') == 5 ? 'checked' : '' }} />
                                <label for="star5" title="5 sao">★</label>
                                <input type="radio" id="star4" name="rating" value="4" {{ old('rating') == 4 ? 'checked' : '' }} />
                                <label for="star4" title="4 sao">★</label>
                                <input type="radio" id="star3" name="rating" value="3" {{ old('rating') == 3 ? 'checked' : '' }} />
                                <label for="star3" title="3 sao">★</label>
                                <input type="radio" id="star2" name="rating" value="2" {{ old('rating') == 2 ? 'checked' : '' }} />
                                <label for="star2" title="2 sao">★</label>
                                <input type="radio" id="star1" name="rating" value="1" {{ old('rating') == 1 ? 'checked' : '' }} />
                                <label for="star1" title="1 sao">★</label>
                            </div>
                            @error('rating')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="noi_dung" class="form-label fw-bold">
                                Nội dung đánh giá <span class="text-danger">*</span>
                            </label>
                            <textarea name="noi_dung" id="noi_dung" rows="5"
                                      class="form-control @error('noi_dung') is-invalid @enderror"
                                      placeholder="Chia sẻ trải nghiệm của bạn về dịch vụ và bác sĩ...">{{ old('noi_dung') }}</textarea>
                            @error('noi_dung')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Tối thiểu 10 ký tự, tối đa 1000 ký tự</small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Gửi đánh giá
                            </button>
                            <a href="{{ route('lichhen.my') }}" class="btn btn-secondary">
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Hướng dẫn</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Đánh giá sao từ 1-5
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Viết nhận xét chi tiết
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Chia sẻ trải nghiệm thực tế
                        </li>
                        <li class="mb-2">
                            <i class="bi bi-check-circle text-success"></i>
                            Giúp bệnh nhân khác có thông tin hữu ích
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

<style>
.star-rating {
    direction: rtl;
    display: inline-flex;
    font-size: 3rem;
}

.star-rating input[type="radio"] {
    display: none;
}

.star-rating label {
    color: #ddd;
    cursor: pointer;
    margin: 0 2px;
    transition: color 0.2s;
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input[type="radio"]:checked ~ label {
    color: #ffc107;
}
</style>
@endsection
