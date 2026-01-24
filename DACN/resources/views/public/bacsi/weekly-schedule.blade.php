@php
    $isPatient = auth()->check() && auth()->user()->isPatient();
    $layout = $isPatient ? 'layouts.patient-modern' : 'layouts.app';
@endphp

@extends($layout)

@section('title', 'Lịch khám - ' . $bacSi->ho_ten)

@section('content')
    <div class="container py-4">
        {{-- 1. HEADER: THÔNG TIN BÁC SĨ --}}
        <div class="card shadow-sm border-0 mb-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-md-8 p-4 d-flex align-items-center bg-white">
                        <div class="d-flex align-items-center">
                            {{-- Avatar --}}
                            <div class="me-4 position-relative">
                                @if ($bacSi->avatar_url)
                                    <img src="{{ $bacSi->avatar_url }}" class="rounded-circle shadow-sm object-fit-cover"
                                        width="80" height="80" alt="{{ $bacSi->ho_ten }}">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-primary"
                                        style="width: 80px; height: 80px;">
                                        <i class="fas fa-user-md fa-2x"></i>
                                    </div>
                                @endif
                                <div class="position-absolute bottom-0 end-0 p-1 bg-white rounded-circle">
                                    <i class="fas fa-check-circle text-success fs-5"></i>
                                </div>
                            </div>

                            <div>
                                <h4 class="fw-bold mb-1">{{ $bacSi->ho_ten ?? optional($bacSi->user)->name }}</h4>
                                @php
                                    $spec =
                                        optional($bacSi->chuyenKhoas->first())->ten ??
                                        ($bacSi->chuyen_khoa ?? 'Đa khoa');
                                @endphp
                                <div class="text-muted mb-2">
                                    <i class="fas fa-stethoscope text-info me-1"></i>
                                    {{ $spec }}
                                </div>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        <i
                                            class="fas fa-star me-1"></i>{{ number_format(\App\Models\DanhGia::getAverageRating($bacSi->id), 1) }}
                                    </span>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        <i
                                            class="fas fa-user-friends me-1"></i>{{ \App\Models\DanhGia::getTotalReviews($bacSi->id) }}
                                        đánh giá
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 bg-light p-4 d-flex flex-column justify-content-center border-start">
                        <a href="{{ route('public.bacsi.index') }}" class="btn btn-outline-secondary w-100 mb-2">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                        </a>
                        <div class="small text-muted text-center">
                            <i class="fas fa-info-circle me-1"></i>Chọn khung giờ bên dưới để đặt lịch
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. LỊCH KHÁM (Weekly Schedule) --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0 fw-bold"><i class="far fa-calendar-alt me-2 text-primary"></i>Lịch làm việc</h5>

                {{-- Điều hướng tuần --}}
                <div class="btn-group shadow-sm">
                    <a href="{{ route('public.bacsi.schedule', ['bacSi' => $bacSi->id, 'week_start' => $weekStart->copy()->subWeek()->format('Y-m-d')]) }}"
                        class="btn btn-light border">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <button type="button" class="btn btn-white border px-3 fw-bold disabled">
                        {{ $weekStart->format('d/m') }} - {{ $weekEnd->format('d/m/Y') }}
                    </button>
                    <a href="{{ route('public.bacsi.schedule', ['bacSi' => $bacSi->id, 'week_start' => $weekStart->copy()->addWeek()->format('Y-m-d')]) }}"
                        class="btn btn-light border">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 text-center align-middle" style="min-width: 800px;">
                        <thead class="bg-light text-secondary">
                            <tr>
                                @php $currentDay = $weekStart->copy(); @endphp
                                @for ($i = 0; $i < 7; $i++)
                                    <th class="py-3 {{ $i >= 5 ? 'text-danger bg-danger bg-opacity-10' : '' }}"
                                        style="width: 14.28%;">
                                        <div class="small text-uppercase">{{ $i == 6 ? 'Chủ nhật' : 'Thứ ' . ($i + 2) }}
                                        </div>
                                        <div class="fs-5 fw-bold">{{ $currentDay->format('d/m') }}</div>
                                    </th>
                                    @php $currentDay->addDay(); @endphp
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @php $currentDay = $weekStart->copy(); @endphp
                                @for ($i = 0; $i < 7; $i++)
                                    @php
                                        $dateStr = $currentDay->format('Y-m-d');
                                        $daySlots = $slotsByDate->get($dateStr, collect());
                                    @endphp
                                    <td class="align-top p-2 bg-white">
                                        @if ($daySlots->isNotEmpty())
                                            <div class="d-grid gap-2">
                                                @foreach ($daySlots as $slot)
                                                    <button
                                                        onclick="bookSlot('{{ $dateStr }}', '{{ $slot['start'] }}')"
                                                        class="btn btn-outline-primary btn-sm py-2 rounded-3 hover-scale transition-all">
                                                        {{ $slot['start'] }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="py-4 text-muted opacity-50">
                                                <i class="far fa-calendar-times mb-1"></i><br>
                                                <small>Trống</small>
                                            </div>
                                        @endif
                                    </td>
                                    @php $currentDay->addDay(); @endphp
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light py-3">
                <div class="d-flex align-items-center small text-muted">
                    <i class="fas fa-info-circle text-primary me-2"></i>
                    <span>Thời gian khám mỗi ca là <strong>30 phút</strong>. Vui lòng đến sớm 10 phút để làm thủ tục.</span>
                </div>
            </div>
        </div>

        {{-- 3. ĐÁNH GIÁ (Reviews) --}}
        @php
            $avgRating = \App\Models\DanhGia::getAverageRating($bacSi->id);
            $totalReviews = \App\Models\DanhGia::getTotalReviews($bacSi->id);
            $reviews = \App\Models\DanhGia::where('bac_si_id', $bacSi->id)
                ->approved()
                ->with('user')
                ->latest()
                ->take(5)
                ->get();
        @endphp

        @if ($totalReviews > 0)
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Đánh giá từ bệnh nhân ({{ $totalReviews }})</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        {{-- Cột trái: Tổng quan điểm số --}}
                        <div class="col-md-4 text-center border-end">
                            <div class="display-3 fw-bold text-dark">{{ number_format($avgRating, 1) }}</div>
                            <div class="mb-2 text-warning fs-5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="{{ $i <= round($avgRating) ? 'fas' : 'far' }} fa-star"></i>
                                @endfor
                            </div>
                            <p class="text-muted">Dựa trên {{ $totalReviews }} lượt đánh giá</p>
                        </div>

                        {{-- Cột phải: Danh sách comment --}}
                        <div class="col-md-8">
                            <div class="list-group list-group-flush">
                                @foreach ($reviews as $review)
                                    <div class="list-group-item px-0 py-3 border-bottom">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="fw-bold mb-0 text-dark">{{ $review->user->name }}</h6>
                                                <div class="text-warning small">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-0 text-secondary small bg-light p-2 rounded">
                                            <i class="fas fa-quote-left text-muted me-2 opacity-50"></i>
                                            {{ $review->noi_dung }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            function bookSlot(date, time) {
                // Hiệu ứng loading nút bấm
                const btn = event.currentTarget;
                const originalText = btn.innerText;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                btn.classList.add('disabled');

                const url = "{{ route('lichhen.create', ['bacSi' => $bacSi->id]) }}";
                const params = new URLSearchParams({
                    ngay: date,
                    gio: time
                });

                setTimeout(() => {
                    window.location.href = `${url}?${params.toString()}`;
                }, 300);
            }
        </script>
    @endpush

    @push('styles')
        <style>
            .hover-scale:hover {
                transform: scale(1.05);
                background-color: var(--bs-primary);
                color: white;
            }

            .transition-all {
                transition: all 0.2s ease;
            }

            /* Ẩn scrollbar ngang của bảng trên desktop nhưng vẫn scroll được nếu màn nhỏ */
            .table-responsive::-webkit-scrollbar {
                height: 6px;
            }

            .table-responsive::-webkit-scrollbar-thumb {
                background-color: #dee2e6;
                border-radius: 4px;
            }
        </style>
    @endpush
@endsection
