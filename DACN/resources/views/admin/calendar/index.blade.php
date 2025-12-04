@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">

        {{-- ============================
         🔥 HEADER: Lịch làm việc
    ============================= --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0"><i class="fas fa-calendar-alt me-2"></i> Lịch làm việc của Bác sĩ</h2>
        </div>


        {{-- ============================
         🔥 Bộ lọc bác sĩ + quản lý + thống kê
    ============================= --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body d-flex flex-wrap gap-3 align-items-end">

                {{-- Chọn bác sĩ --}}
                <div>
                    <label class="form-label fw-bold mb-1">Chọn bác sĩ</label>
                    <select id="doctorSelect" class="form-select form-select-sm" style="min-width:240px">
                        @isset($doctors)
                            @foreach ($doctors as $d)
                                <option value="{{ $d->id }}">
                                    Bác sĩ {{ $d->ho_ten ?? '' }}
                                    {{ $d->chuyen_khoa ? '(' . $d->chuyen_khoa . ')' : '' }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                {{-- Nút reload --}}
                <button id="reloadBtn" class="btn btn-primary btn-sm">
                    <i class="fas fa-sync-alt me-1"></i> Làm mới
                </button>

                {{-- Quick actions --}}
                <div class="dropdown ms-auto">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-cog"></i> Quản lý lịch
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow" id="quickLinks">
                        <li>
                            <h6 class="dropdown-header fw-bold">Quản lý lịch bác sĩ</h6>
                        </li>
                        <li><a class="dropdown-item" data-route="lichlamviec"><i class="fas fa-calendar-week me-2"></i> Lịch
                                làm việc</a></li>
                        <li><a class="dropdown-item" data-route="lichnghi"><i class="fas fa-calendar-times me-2"></i> Lịch
                                nghỉ</a></li>
                        <li><a class="dropdown-item" data-route="cadieuchinh"><i class="fas fa-calendar-alt me-2"></i> Ca
                                điều chỉnh</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="{{ route('admin.bac-si.index') }}"><i
                                    class="fas fa-user-md me-2"></i> Danh sách bác sĩ</a></li>
                    </ul>
                </div>

                {{-- Stats --}}
                <span id="statsText" class="fw-semibold small ms-2 text-primary">
                    Đang tải dữ liệu...
                </span>
            </div>
        </div>


        {{-- ============================
         🔥 Lịch FullCalendar
    ============================= --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <div id="calendar"></div>
            </div>
        </div>

    </div>


    {{-- ============================
     FullCalendar CSS/JS
============================ --}}
    <link rel="stylesheet" href="https://unpkg.com/fullcalendar@6.1.11/index.global.min.css">
    <script src="https://unpkg.com/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://unpkg.com/fullcalendar@6.1.11/locales/vi.global.min.js"></script>


    {{-- ============================
     🔥 Script xử lý lịch
============================ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const doctorSelect = document.getElementById('doctorSelect');
            const reloadBtn = document.getElementById('reloadBtn');
            const statsText = document.getElementById('statsText');

            function getDoctorId() {
                return doctorSelect?.value || (doctorSelect?.options[0]?.value ?? '');
            }

            // Quick links
            document.querySelectorAll('#quickLinks a[data-route]').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const route = this.dataset.route;
                    const doctorId = getDoctorId();
                    if (!doctorId) return alert('Vui lòng chọn bác sĩ');

                    const routes = {
                        'lichlamviec': `/admin/lich-lam-viec/${doctorId}`,
                        'lichnghi': `/admin/lich-nghi/${doctorId}`,
                        'cadieuchinh': `/admin/ca-dieu-chinh/${doctorId}`,
                    };

                    window.location.href = routes[route];
                });
            });

            function fetchStats() {
                const d = getDoctorId();
                if (!d) {
                    statsText.textContent = 'Không tải được dữ liệu';
                    return;
                }

                const qs = new URLSearchParams({
                    bac_si_id: d,
                    date: new Date().toISOString().slice(0, 10)
                }).toString();

                fetch(`{{ route('admin.calendar.api.stats2') }}?${qs}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(r => r.ok ? r.json() : Promise.reject())
                    .then(x => {
                        statsText.textContent =
                            `Thống kê — Hẹn: ${x.appointments} | Giờ làm: ${x.working_hours} | Điều chỉnh: ${x.overrides}`;
                    })
                    .catch(() => statsText.textContent = 'Không tải được dữ liệu');
            }

            function sourceApi2() {
                return {
                    id: 'main',
                    url: "{{ route('admin.calendar.api.events2') }}",
                    method: 'GET',
                    extraParams: () => ({
                        bac_si_id: getDoctorId()
                    }),
                };
            }

            // FullCalendar setup
            const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                initialView: 'timeGridWeek',
                locale: 'vi',
                nowIndicator: true,
                editable: true,
                eventOverlap: false,
                eventSources: [sourceApi2()],

                eventDrop(info) {
                    const payload = {
                        id: info.event.id,
                        bac_si_id: info.event.extendedProps?.bac_si_id || Number(getDoctorId()),
                        start: info.event.start.toISOString(),
                        end: (info.event.end || new Date(info.event.start.getTime() + 30 * 60000))
                            .toISOString(),
                        type: info.event.extendedProps?.type || 'lich_hen',
                        raw_id: info.event.extendedProps?.raw_id || null
                    };

                    fetch("{{ route('admin.calendar.api.drag_update') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.content || ''
                            },
                            body: JSON.stringify(payload)
                        })
                        .then(r => {
                            if (!r.ok) info.revert();
                            else fetchStats();
                        })
                        .catch(() => info.revert());
                },

                datesSet() {
                    fetchStats();
                }
            });

            calendar.render();

            reloadBtn.addEventListener('click', () => {
                calendar.getEventSources().forEach(s => s.remove());
                calendar.addEventSource(sourceApi2());
                calendar.refetchEvents();
                fetchStats();
            });

            fetchStats();
        });
    </script>


    {{-- ============================
     🔥 CSS tùy chỉnh cho Calendar UI
============================ --}}
    <style>
        #calendar {
            min-height: 700px;
        }

        .fc-toolbar-title {
            font-size: 22px !important;
            font-weight: bold;
            color: #333;
        }

        .fc-button {
            border-radius: 6px !important;
        }

        .fc-event {
            font-size: 13px;
            padding: 3px 4px !important;
            border-radius: 6px;
        }
    </style>

@endsection
