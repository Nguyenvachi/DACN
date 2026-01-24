@extends('layouts.patient-modern')

@section('content')
    <div class="container-fluid py-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-primary">
                    <i class="bi bi-star-fill text-warning me-2"></i>
                    Đánh giá bác sĩ
                </h2>
                <p class="text-muted mb-0 mt-1">Chia sẻ trải nghiệm khám bệnh của bạn</p>
            </div>
            <a href="{{ route('lichhen.my') }}" class="btn btn-light shadow-sm rounded-pill hover-scale">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-circle-fill fs-4 me-3"></i>
                    <div>
                        <strong>Có lỗi xảy ra!</strong> {{ session('error') }}
                    </div>
                </div>
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            {{-- Main Form Column --}}
            <div class="col-lg-8">
                <div class="card shadow border-0 rounded-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <h5 class="mb-0 fw-bold text-dark border-start border-4 border-primary ps-3">
                            Thông tin trải nghiệm
                        </h5>
                    </div>

                    <div class="card-body pt-0">
                        {{-- Doctor Info Card --}}
                        <div class="bg-light bg-opacity-50 p-4 rounded-4 mb-4 border border-light">
                            <div class="d-flex align-items-center">
                                {{-- THÊM CODE: Hiển thị Avatar bác sĩ --}}
                                <div class="me-4 flex-shrink-0">
                                    @if (isset($lichHen->bacSi->avatar) && $lichHen->bacSi->avatar)
                                        <img src="{{ asset('storage/' . $lichHen->bacSi->avatar) }}"
                                            class="rounded-circle shadow-sm" width="80" height="80"
                                            style="object-fit: cover; border: 3px solid #fff;">
                                    @else
                                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center shadow-sm"
                                            style="width: 80px; height: 80px; font-size: 2rem; border: 3px solid #eef0f2;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-grow-1">
                                    <h5 class="fw-bold mb-1 text-primary">{{ $lichHen->bacSi->ho_ten }}</h5>
                                    <div class="row g-2 mt-2">
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Dịch vụ</small>
                                            <span class="fw-medium"><i class="bi bi-activity text-info me-1"></i>
                                                {{ $lichHen->dichVu->ten_dich_vu }}</span>
                                        </div>
                                        <div class="col-md-6">
                                            <small class="text-muted d-block">Thời gian khám</small>
                                            <span class="fw-medium">
                                                <i class="bi bi-clock text-warning me-1"></i>
                                                {{ $lichHen->thoi_gian_hen }} -
                                                {{ \Carbon\Carbon::parse($lichHen->ngay_hen)->format('d/m/Y') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="text-muted opacity-10 my-4">

                        <form action="{{ route('patient.danhgia.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="lich_hen_id" value="{{ $lichHen->id }}">

                            {{-- Rating Section --}}
                            <div class="mb-5 text-center">
                                <label
                                    class="form-label fw-bold mb-3 d-block text-secondary text-uppercase small spacing-1">
                                    Bạn chấm mấy sao cho dịch vụ này? <span class="text-danger">*</span>
                                </label>

                                <div class="star-rating-wrapper">
                                    <div class="star-rating">
                                        <input type="radio" id="star5" name="rating" value="5"
                                            {{ old('rating') == 5 ? 'checked' : '' }} />
                                        <label for="star5" title="Tuyệt vời - 5 sao"><i
                                                class="bi bi-star-fill"></i></label>

                                        <input type="radio" id="star4" name="rating" value="4"
                                            {{ old('rating') == 4 ? 'checked' : '' }} />
                                        <label for="star4" title="Tốt - 4 sao"><i class="bi bi-star-fill"></i></label>

                                        <input type="radio" id="star3" name="rating" value="3"
                                            {{ old('rating') == 3 ? 'checked' : '' }} />
                                        <label for="star3" title="Bình thường - 3 sao"><i
                                                class="bi bi-star-fill"></i></label>

                                        <input type="radio" id="star2" name="rating" value="2"
                                            {{ old('rating') == 2 ? 'checked' : '' }} />
                                        <label for="star2" title="Tệ - 2 sao"><i class="bi bi-star-fill"></i></label>

                                        <input type="radio" id="star1" name="rating" value="1"
                                            {{ old('rating') == 1 ? 'checked' : '' }} />
                                        <label for="star1" title="Rất tệ - 1 sao"><i class="bi bi-star-fill"></i></label>
                                    </div>
                                </div>
                                @error('rating')
                                    <div
                                        class="text-danger small mt-2 bg-danger bg-opacity-10 d-inline-block px-3 py-1 rounded-pill">
                                        <i class="bi bi-exclamation-triangle me-1"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Review Content --}}
                            <div class="mb-4">
                                <label for="noi_dung" class="form-label fw-bold">
                                    Nội dung đánh giá <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-lg shadow-sm">
                                    <span class="input-group-text bg-white border-end-0 text-muted">
                                        <i class="bi bi-chat-quote"></i>
                                    </span>
                                    <textarea name="noi_dung" id="noi_dung" rows="5"
                                        class="form-control border-start-0 ps-0 @error('noi_dung') is-invalid @enderror"
                                        placeholder="Bác sĩ có nhiệt tình không? Cơ sở vật chất thế nào?...">{{ old('noi_dung') }}</textarea>
                                </div>
                                @error('noi_dung')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="text-muted">Tối thiểu 10 ký tự</small>
                                    <small class="text-muted">Tối đa 1000 ký tự</small>
                                </div>
                            </div>

                            <div class="d-flex gap-3 pt-3">
                                <button type="submit"
                                    class="btn btn-primary rounded-pill px-4 py-2 shadow-sm hover-up flex-grow-1 flex-md-grow-0">
                                    <i class="bi bi-send-fill me-2"></i> Gửi đánh giá
                                </button>
                                <a href="{{ route('lichhen.my') }}"
                                    class="btn btn-light rounded-pill px-4 py-2 border hover-up">
                                    Hủy bỏ
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Guide Column --}}
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded-4 bg-primary bg-opacity-10 h-100">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-lightbulb-fill text-warning fs-3"></i>
                            </div>
                            <h5 class="fw-bold mt-3">Góc chia sẻ</h5>
                        </div>

                        <ul class="list-unstyled custom-list">
                            <li class="mb-3 d-flex">
                                <i class="bi bi-check-circle-fill text-success me-3 mt-1"></i>
                                <span>Đánh giá khách quan giúp chúng tôi cải thiện chất lượng dịch vụ.</span>
                            </li>
                            <li class="mb-3 d-flex">
                                <i class="bi bi-shield-check text-primary me-3 mt-1"></i>
                                <span>Thông tin của bạn được bảo mật, nhưng nội dung đánh giá sẽ được công khai.</span>
                            </li>
                            <li class="mb-3 d-flex">
                                <i class="bi bi-heart-fill text-danger me-3 mt-1"></i>
                                <span>Hãy chia sẻ những điều bạn hài lòng hoặc chưa hài lòng về bác sĩ.</span>
                            </li>
                        </ul>

                        <div class="mt-4 p-3 bg-white rounded-3 shadow-sm text-center">
                            <small class="text-muted d-block mb-1">Cần hỗ trợ?</small>
                            <span class="fw-bold text-primary">1900 1234</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <style>
            /* Styling for Star Rating */
            .star-rating-wrapper {
                display: inline-block;
                background: #fff;
                padding: 10px 20px;
                border-radius: 50px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            }

            .star-rating {
                direction: rtl;
                display: inline-flex;
                font-size: 2.5rem;
            }

            .star-rating input[type="radio"] {
                display: none;
            }

            .star-rating label {
                color: #e4e5e9;
                cursor: pointer;
                margin: 0 5px;
                transition: all 0.2s ease;
            }

            .star-rating label:hover,
            .star-rating label:hover~label,
            .star-rating input[type="radio"]:checked~label {
                color: #ffc107;
                transform: scale(1.1);
            }

            /* General Hover Effects */
            .hover-up {
                transition: transform 0.2s;
            }

            .hover-up:hover {
                transform: translateY(-2px);
            }

            .hover-scale:hover {
                transform: scale(1.05);
                transition: transform 0.2s;
            }

            /* Input Styling */
            .form-control:focus {
                box-shadow: none;
                border-color: #dee2e6;
            }

            .input-group:focus-within {
                box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15) !important;
                border-radius: 0.375rem;
            }

            .input-group:focus-within .input-group-text,
            .input-group:focus-within .form-control {
                border-color: #86b7fe;
            }
        </style>
    @endpush
@endsection
