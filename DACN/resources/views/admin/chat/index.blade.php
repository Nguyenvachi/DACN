@extends('layouts.admin')

@section('title', 'Quản lý Chat')

@section('content')
    <div class="container-fluid py-4">
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Tổng cuộc hội thoại</p>
                                <h3 class="mb-0">{{ $stats['total'] }}</h3>
                            </div>
                            <div class="text-primary">
                                <i class="bi bi-chat-dots fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Đang hoạt động</p>
                                <h3 class="mb-0">{{ $stats['active'] }}</h3>
                            </div>
                            <div class="text-success">
                                <i class="bi bi-chat-text fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Đã đóng</p>
                                <h3 class="mb-0">{{ $stats['closed'] }}</h3>
                            </div>
                            <div class="text-secondary">
                                <i class="bi bi-archive fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1">Tổng tin nhắn</p>
                                <h3 class="mb-0">{{ $stats['total_messages'] }}</h3>
                            </div>
                            <div class="text-info">
                                <i class="bi bi-envelope fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Tên bệnh nhân, bác sĩ..."
                            value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="trang_thai" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="Đang hoạt động"
                                {{ request('trang_thai') == 'Đang hoạt động' ? 'selected' : '' }}>Đang hoạt động</option>
                            <option value="Đã đóng" {{ request('trang_thai') == 'Đã đóng' ? 'selected' : '' }}>Đã đóng
                            </option>
                            <option value="Bị khóa" {{ request('trang_thai') == 'Bị khóa' ? 'selected' : '' }}>Bị khóa
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Lọc
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Conversations Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Danh sách cuộc hội thoại</h5>
            </div>
            <div class="card-body">
                @if($conversations->count() > 0)
                    <table id="chatTable" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Bệnh nhân</th>
                                <th>Bác sĩ</th>
                                <th>Tin nhắn cuối</th>
                                <th>Thời gian</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($conversations as $conversation)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <i class="bi bi-person-circle fs-4 text-secondary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $conversation->benhNhan->name }}</div>
                                                <small class="text-muted">{{ $conversation->benhNhan->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-medium">{{ $conversation->bacSi->ho_ten }}</div>
                                        <small class="text-muted">{{ $conversation->bacSi->chuyen_khoa }}</small>
                                    </td>
                                    <td>
                                        @if ($conversation->latestMessage)
                                            <div class="text-truncate" style="max-width: 300px;">
                                                {{ Str::limit($conversation->latestMessage->noi_dung, 50) }}
                                            </div>
                                        @else
                                            <small class="text-muted">Chưa có tin nhắn</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($conversation->last_message_at)
                                            <small>{{ $conversation->last_message_at->diffForHumans() }}</small>
                                        @else
                                            <small class="text-muted">-</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($conversation->trang_thai == 'Đang hoạt động')
                                            <span class="badge bg-success">Đang hoạt động</span>
                                        @elseif($conversation->trang_thai == 'Đã đóng')
                                            <span class="badge bg-secondary">Đã đóng</span>
                                        @else
                                            <span class="badge bg-danger">Bị khóa</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.chat.show', $conversation) }}"
                                            class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.chat.destroy', $conversation) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa cuộc hội thoại này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-chat-dots fs-1 text-muted d-block mb-3"></i>
                        <p class="text-muted">Chưa có cuộc hội thoại nào</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#chatTable').DataTable({
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/vi.json'
                    },
                    order: [
                        [3, 'desc']
                    ],
                    pageLength: 20
                });
            });
        </script>
    @endpush
@endsection
