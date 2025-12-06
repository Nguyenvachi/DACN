@extends('layouts.patient-modern')

@section('title', 'Thông báo')
@section('page-title', 'Thông báo')
@section('page-subtitle', 'Trung tâm thông báo của bạn')

@section('content')
<div class="row g-4">
    {{-- Filter --}}
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div class="btn-group" role="group">
                        <a href="{{ route('patient.notifications') }}" class="btn btn-sm {{ !request('filter') ? 'btn-primary' : 'btn-outline-primary' }}">
                            Tất cả ({{ $allCount }})
                        </a>
                        <a href="{{ route('patient.notifications', ['filter' => 'unread']) }}" class="btn btn-sm {{ request('filter') === 'unread' ? 'btn-primary' : 'btn-outline-primary' }}">
                            Chưa đọc ({{ $unreadCount }})
                        </a>
                        <a href="{{ route('patient.notifications', ['filter' => 'read']) }}" class="btn btn-sm {{ request('filter') === 'read' ? 'btn-primary' : 'btn-outline-primary' }}">
                            Đã đọc
                        </a>
                    </div>

                    @if($unreadCount > 0)
                        <form action="{{ route('patient.notifications.mark-all-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fas fa-check-double me-1"></i>Đánh dấu tất cả đã đọc
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Danh sách thông báo --}}
    <div class="col-12">
        @forelse($notifications as $notification)
            <div class="card mb-3 {{ $notification->read_at ? '' : 'border-start border-primary border-4' }}">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle p-3 {{ $notification->read_at ? 'bg-light' : 'bg-primary bg-opacity-10' }}">
                                @php
                                    $icon = 'bell';
                                    $iconColor = 'text-muted';

                                    if(str_contains($notification->type, 'Appointment')) {
                                        $icon = 'calendar-check';
                                        $iconColor = 'text-primary';
                                    } elseif(str_contains($notification->type, 'Payment')) {
                                        $icon = 'credit-card';
                                        $iconColor = 'text-success';
                                    } elseif(str_contains($notification->type, 'Medical')) {
                                        $icon = 'file-medical';
                                        $iconColor = 'text-info';
                                    } elseif(str_contains($notification->type, 'Order')) {
                                        $icon = 'shopping-cart';
                                        $iconColor = 'text-warning';
                                    }
                                @endphp
                                <i class="fas fa-{{ $icon }} {{ $iconColor }} fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="mb-0 {{ $notification->read_at ? 'text-muted' : '' }}">
                                    {{ $notification->data['title'] ?? 'Thông báo' }}
                                </h6>
                                <small class="text-muted">{{ $notification->created_at?->diffForHumans() }}</small>
                            </div>
                            <p class="mb-2 {{ $notification->read_at ? 'text-muted' : '' }}">
                                {{ $notification->data['message'] ?? '' }}
                            </p>

                            @if(isset($notification->data['action_url']))
                                <a href="{{ $notification->data['action_url'] }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt me-1"></i>Xem chi tiết
                                </a>
                            @endif

                            @if(!$notification->read_at)
                                <form action="{{ route('patient.notifications.mark-read', $notification) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success ms-2">
                                        <i class="fas fa-check me-1"></i>Đánh dấu đã đọc
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('patient.notifications.delete', $notification) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger ms-2" onclick="return confirm('Bạn có chắc muốn xóa thông báo này?')">
                                    <i class="fas fa-trash me-1"></i>Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-bell-slash fs-1 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">Không có thông báo</h5>
                    <p class="text-muted">Bạn sẽ nhận được thông báo về lịch hẹn, thanh toán và các hoạt động khác tại đây</p>
                </div>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="mt-4">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
