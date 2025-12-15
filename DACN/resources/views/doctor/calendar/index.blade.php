@php
    // Xác định layout theo role
    $role = auth()->check() ? auth()->user()->roleKey() : 'doctor';
    // Ưu tiên layout riêng cho bác sĩ, tránh thay đổi cấu trúc khác
    $layout = $role === 'doctor' ? 'layouts.doctor' : (in_array($role, ['admin']) ? 'layouts.admin' : 'layouts.app');
@endphp

@extends($layout)

@section('content')
    <div class="container-fluid py-4">

        {{-- Tiêu đề --}}
        <h3 class="mb-4">
            🗓️ Lịch làm việc của tôi
        </h3>

        {{-- Thông tin bác sĩ --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <p class="mb-0 text-muted">
                    Bác sĩ: <strong>{{ $bacSi->ho_ten }}</strong>
                </p>
            </div>
        </div>

        {{-- Calendar --}}
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="calendar" data-events-url="{{ route('doctor.calendar.events') }}">
                </div>
            </div>
        </div>

    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css">
    <style>
        /* Tối ưu giao diện FullCalendar trong Bootstrap Card */
        #calendar {
            min-height: 600px;
        }

        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .fc-event {
            font-size: 0.85rem;
            padding: 2px 4px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                locale: 'vi',
                slotMinTime: '07:00:00',
                slotMaxTime: '20:00:00',
                height: 'auto',
                nowIndicator: true,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: {
                    url: calendarEl.dataset.eventsUrl,
                    method: 'GET',
                    failure() {
                        alert('Không tải được dữ liệu lịch.');
                    }
                }
            });
            calendar.render();
        });
    </script>
@endpush
