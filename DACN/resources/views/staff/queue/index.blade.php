@extends('layouts.staff')

@section('title', 'Quản Lý Hàng Đợi')

@section('content')
{{-- File: resources/views/staff/queue/index.blade.php --}}
{{-- Parent: resources/views/staff/ --}}

<div class="container-fluid px-4 py-4">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="bi bi-people me-2 text-success"></i>Quản Lý Hàng Đợi</h2>
            <p class="text-muted mb-0">Theo dõi và điều phối hàng đợi khám bệnh</p>
        </div>
        <div>
            <span class="badge bg-light text-dark fs-6"><i class="bi bi-clock me-1"></i><span id="currentTime"></span></span>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4 g-4">
        <div class="col-lg-3 col-md-6">
            <div class="card border-0 h-100 stat-card-modern"
                 style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); box-shadow: 0 4px 15px rgba(245, 158, 11, 0.4);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-circle bg-white bg-opacity-25">
                            <i class="bi bi-hourglass-split fs-2"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-white text-opacity-75 mb-1 small">Đang chờ</p>
                            <h3 class="mb-0 fw-bold" id="stat-waiting">{{ $statistics['waiting'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 h-100 stat-card-modern"
                 style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-circle bg-white bg-opacity-25">
                            <i class="bi bi-person-hearts fs-2"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-white text-opacity-75 mb-1 small">Đang khám</p>
                            <h3 class="mb-0 fw-bold" id="stat-progress">{{ $statistics['in_progress'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 h-100 stat-card-modern"
                 style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-circle bg-white bg-opacity-25">
                            <i class="bi bi-check-circle fs-2"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-white text-opacity-75 mb-1 small">Hoàn thành</p>
                            <h3 class="mb-0 fw-bold" id="stat-completed">{{ $statistics['completed_today'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card border-0 h-100 stat-card-modern"
                 style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);">
                <div class="card-body text-white p-4">
                    <div class="d-flex align-items-center">
                        <div class="p-3 rounded-circle bg-white bg-opacity-25">
                            <i class="bi bi-stopwatch fs-2"></i>
                        </div>
                        <div class="ms-3">
                            <p class="text-white text-opacity-75 mb-1 small">Thời gian chờ TB</p>
                            <h3 class="mb-0 fw-bold">{{ $statistics['avg_wait_time'] }} phút</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Waiting Queue --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning text-white border-0">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-hourglass-split me-2"></i>Hàng Đợi (<span id="queue-count">{{ $queue->count() }}</span>)</h5>
                </div>
                <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                    <div id="queueList">
                        @forelse($queue as $index => $apt)
                            <div class="list-group-item border-0 border-bottom p-3 queue-item" data-id="{{ $apt->id }}">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 60px; height: 60px; border: 3px solid rgba(245, 158, 11, 0.3);">
                                            <div class="text-center">
                                                <small class="d-block" style="font-size: 0.7rem; line-height: 1;">STT</small>
                                                <h4 class="mb-0 fw-bold" style="line-height: 1;">{{ $index + 1 }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 fw-bold">{{ $apt->user->name }}</h6>
                                        <p class="mb-0 text-muted small">
                                            <i class="bi bi-person-badge me-1"></i>BS. {{ $apt->bacSi->ho_ten }}
                                            <span class="mx-2">|</span>
                                            <i class="bi bi-stethoscope me-1"></i>{{ $apt->dichVu->ten_dich_vu }}
                                        </p>
                                        @if($apt->checked_in_at)
                                            <p class="mb-0 text-muted small">
                                                <i class="bi bi-clock me-1"></i>Check-in: {{ \Carbon\Carbon::parse($apt->checked_in_at)->format('H:i') }}
                                                <span class="badge bg-warning text-dark ms-2">Chờ: {{ \Carbon\Carbon::parse($apt->checked_in_at)->diffInMinutes(now()) }} phút</span>
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-end">
                                        <form method="POST" action="{{ route('staff.queue.call_next', $apt) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm"
                                                    onclick="return confirm('Gọi {{ $apt->user->name }} vào khám?')">
                                                <i class="bi bi-telephone-forward me-1"></i>Gọi Vào
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted" id="emptyQueue">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                <p>Không có bệnh nhân đang chờ</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- In Progress --}}
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white border-0">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-hearts me-2"></i>Đang Khám ({{ $inProgress->count() }})</h5>
                </div>
                <div class="card-body p-0" style="max-height: 600px; overflow-y: auto;">
                    @forelse($inProgress as $apt)
                        <div class="list-group-item border-0 border-bottom p-3">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded p-2">
                                        <i class="bi bi-person-hearts fs-4"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1 fw-bold">{{ $apt->user->name }}</h6>
                                    <p class="mb-1 text-muted small">
                                        <i class="bi bi-person-badge me-1"></i>BS. {{ $apt->bacSi->ho_ten }}
                                    </p>
                                    <p class="mb-0 text-muted small">
                                        <i class="bi bi-stethoscope me-1"></i>{{ $apt->dichVu->ten_dich_vu }}
                                    </p>
                                    @if($apt->thoi_gian_bat_dau_kham)
                                        <p class="mb-0 small mt-1">
                                            <span class="badge bg-info">
                                                <i class="bi bi-clock me-1"></i>Bắt đầu: {{ \Carbon\Carbon::parse($apt->thoi_gian_bat_dau_kham)->format('H:i') }}
                                                ({{ \Carbon\Carbon::parse($apt->thoi_gian_bat_dau_kham)->diffInMinutes(now()) }} phút)
                                            </span>
                                        </p>
                                    @endif
                                </div>
                                <div>
                                    <span class="badge bg-primary">Đang khám</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                            <p>Chưa có bệnh nhân đang khám</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Recently Completed --}}
    @if($completed->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white border-0">
                <h5 class="mb-0 fw-bold"><i class="bi bi-check-circle me-2"></i>Hoàn Thành Gần Đây</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Bệnh Nhân</th>
                                <th>Bác Sĩ</th>
                                <th>Dịch Vụ</th>
                                <th>Thời Gian Hoàn Thành</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($completed as $apt)
                                <tr>
                                    <td>{{ $apt->user->name }}</td>
                                    <td>BS. {{ $apt->bacSi->ho_ten }}</td>
                                    <td>{{ $apt->dichVu->ten_dich_vu }}</td>
                                    <td><small>{{ $apt->updated_at->format('H:i') }}</small></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

</div>

<style>
.stat-card-modern {
    transition: transform 0.3s ease;
}
.stat-card-modern:hover {
    transform: translateY(-5px);
}
.queue-item {
    transition: all 0.3s ease;
}
.queue-item:hover {
    background-color: #f8f9fa;
}
</style>

@push('scripts')
<script>
// Auto-refresh every 30 seconds
let refreshInterval;

function startAutoRefresh() {
    refreshInterval = setInterval(() => {
        fetchRealtimeData();
    }, 30000); // 30 seconds
}

function fetchRealtimeData() {
    fetch('{{ route("staff.queue.realtime") }}')
        .then(response => response.json())
        .then(data => {
            // Update statistics
            document.getElementById('stat-waiting').textContent = data.statistics.waiting;
            document.getElementById('stat-progress').textContent = data.statistics.in_progress;
            document.getElementById('stat-completed').textContent = data.statistics.completed;
            document.getElementById('queue-count').textContent = data.statistics.waiting;

            console.log('Queue data updated:', data.timestamp);
        })
        .catch(error => {
            console.error('Error fetching realtime data:', error);
        });
}

// Update current time
function updateCurrentTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
    document.getElementById('currentTime').textContent = timeString;
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateCurrentTime();
    setInterval(updateCurrentTime, 1000);
    startAutoRefresh();
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (refreshInterval) clearInterval(refreshInterval);
});
</script>
@endpush

@endsection
