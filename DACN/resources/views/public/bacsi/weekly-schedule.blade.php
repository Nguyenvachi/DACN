@php
    $isPatient = auth()->check() && auth()->user()->role === 'patient';
    $layout = $isPatient ? 'layouts.patient-modern' : 'layouts.app';
@endphp

@extends($layout)

@section('content')
    <style>
        .schedule-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .week-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .schedule-table {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .day-column {
            border: 1px solid #e0e0e0;
            padding: 1rem;
            min-height: 200px;
        }

        .day-header {
            font-weight: bold;
            color: #667eea;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #667eea;
        }

        .day-header.weekend {
            color: #dc3545;
            border-bottom-color: #dc3545;
        }

        .slot-item {
            background: #f0f4ff;
            border-left: 3px solid #667eea;
            padding: 0.5rem;
            margin-bottom: 0.5rem;
            border-radius: 5px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .slot-item:hover {
            background: #667eea;
            color: white;
            transform: translateX(5px);
        }

        .no-slots {
            color: #999;
            font-style: italic;
            text-align: center;
            padding: 1rem;
        }
    </style>

    <div class="container py-4">
        <!-- Header -->
        <div class="schedule-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">
                        <i class="fas fa-calendar-week"></i>
                        Lịch Rảnh - {{ $bacSi->ten }}
                    </h2>
                    <p class="mb-0">
                        <i class="fas fa-stethoscope"></i>
                        {{ $bacSi->chuyenKhoa->ten ?? 'Đa khoa' }}
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('public.bacsi.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>

        <!-- Week Navigation -->
        <div class="week-navigation">
            <a href="{{ route('public.bacsi.schedule', ['bacSi' => $bacSi->id, 'week_start' => $weekStart->copy()->subWeek()->format('Y-m-d')]) }}"
                class="btn btn-outline-primary">
                <i class="fas fa-chevron-left"></i> Tuần trước
            </a>
            <h4 class="mb-0">
                {{ $weekStart->format('d/m/Y') }} - {{ $weekEnd->format('d/m/Y') }}
            </h4>
            <a href="{{ route('public.bacsi.schedule', ['bacSi' => $bacSi->id, 'week_start' => $weekStart->copy()->addWeek()->format('Y-m-d')]) }}"
                class="btn btn-outline-primary">
                Tuần sau <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <!-- Schedule Table -->
        <div class="schedule-table">
            <div class="row g-0">
                @php
                    $daysOfWeek = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];
                    $currentDay = $weekStart->copy();
                @endphp

                @for ($i = 0; $i < 7; $i++)
                    @php
                        $dateStr = $currentDay->format('Y-m-d');
                        $daySlots = $slotsByDate->get($dateStr, collect());
                        $isWeekend = $i >= 5;
                    @endphp

                    <div class="col-md-12 col-lg-{{ 12 / 7 }} day-column">
                        <div class="day-header {{ $isWeekend ? 'weekend' : '' }}">
                            {{ $daysOfWeek[$i] }}<br>
                            <small>{{ $currentDay->format('d/m') }}</small>
                        </div>

                        @if ($daySlots->isNotEmpty())
                            @foreach ($daySlots as $slot)
                                <div class="slot-item" onclick="bookSlot('{{ $dateStr }}', '{{ $slot['start'] }}')">
                                    <i class="far fa-clock"></i>
                                    {{ $slot['start'] }} - {{ $slot['end'] }}
                                </div>
                            @endforeach
                        @else
                            <div class="no-slots">
                                Không có lịch
                            </div>
                        @endif
                    </div>

                    @php
                        $currentDay->addDay();
                    @endphp
                @endfor
            </div>
        </div>

        <!-- Quick Info -->
        <div class="alert alert-info mt-4">
            <i class="fas fa-info-circle"></i>
            <strong>Lưu ý:</strong> Click vào khung giờ để đặt lịch khám. Mỗi slot có thời lượng 30 phút.
        </div>

        <!-- Reviews Section -->
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
            <div class="card mt-4 shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-star-fill text-warning"></i>
                        Đánh giá từ bệnh nhân
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3 text-center border-end">
                            <div class="display-4 fw-bold text-warning">{{ number_format($avgRating, 1) }}</div>
                            <div class="mb-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= round($avgRating))
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @else
                                        <i class="bi bi-star text-muted"></i>
                                    @endif
                                @endfor
                            </div>
                            <div class="text-muted">{{ $totalReviews }} đánh giá</div>
                        </div>
                        <div class="col-md-9">
                            @php
                                $distribution = \App\Models\DanhGia::getRatingDistribution($bacSi->id);
                            @endphp
                            @for ($i = 5; $i >= 1; $i--)
                                @php
                                    $count = $distribution[$i] ?? 0;
                                    $percentage = $totalReviews > 0 ? ($count / $totalReviews) * 100 : 0;
                                @endphp
                                <div class="d-flex align-items-center mb-2">
                                    <span class="me-2" style="width: 60px;">{{ $i }} sao</span>
                                    <div class="progress flex-grow-1" style="height: 20px;">
                                        <div class="progress-bar bg-warning" role="progressbar"
                                             style="width: {{ $percentage }}%"
                                             aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                    <span class="ms-2 text-muted" style="width: 50px;">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <hr>

                    <h6 class="fw-bold mb-3">Đánh giá gần đây</h6>
                    @foreach ($reviews as $review)
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <strong>{{ $review->user->name }}</strong>
                                    <div class="text-warning">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <i class="bi bi-star-fill"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-0">{{ $review->noi_dung }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function bookSlot(date, time) {
            // Chuyển đến trang đặt lịch với thông tin đã chọn
            const url = "{{ route('lichhen.create', ['bacSi' => $bacSi->id]) }}";
            const params = new URLSearchParams({
                ngay: date,
                gio: time
            });
            window.location.href = `${url}?${params.toString()}`;
        }
    </script>
@endsection
