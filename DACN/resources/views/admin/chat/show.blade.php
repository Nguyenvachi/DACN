@extends('layouts.admin')

@section('title', 'Chi tiết cuộc hội thoại')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <!-- Sidebar - Conversation Info -->
            <div class="col-md-3">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white">
                        <h6 class="mb-0">Thông tin cuộc hội thoại</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="text-muted small">Bệnh nhân</label>
                            <div class="fw-medium">{{ $conversation->benhNhan->name }}</div>
                            <small class="text-muted">{{ $conversation->benhNhan->email }}</small>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Bác sĩ</label>
                            <div class="fw-medium">{{ $conversation->bacSi->ho_ten }}</div>
                            <small class="text-muted">{{ $conversation->bacSi->chuyen_khoa }}</small>
                        </div>

                        @if ($conversation->lichHen)
                            <div class="mb-3">
                                <label class="text-muted small">Lịch hẹn liên quan</label>
                                <div>
                                    <div class="small">
                                        <strong>Ngày:</strong> {{ $conversation->lichHen->ngay_hen->format('d/m/Y') }}<br>
                                        <strong>Giờ:</strong> {{ $conversation->lichHen->thoi_gian_hen }}<br>
                                        <strong>Dịch vụ:</strong> {{ $conversation->lichHen->dichVu->ten_dich_vu ?? '-' }}
                                    </div>
                                    <a href="{{ route('admin.lichhen.index') }}"
                                        class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="bi bi-calendar-check"></i> Xem danh sách lịch hẹn
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="text-muted small">Trạng thái</label>
                            <form action="{{ route('admin.chat.update-status', $conversation) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="trang_thai" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="Đang hoạt động"
                                        {{ $conversation->trang_thai == 'Đang hoạt động' ? 'selected' : '' }}>Đang hoạt động
                                    </option>
                                    <option value="Đã đóng" {{ $conversation->trang_thai == 'Đã đóng' ? 'selected' : '' }}>
                                        Đã đóng</option>
                                    <option value="Bị khóa" {{ $conversation->trang_thai == 'Bị khóa' ? 'selected' : '' }}>
                                        Bị khóa</option>
                                </select>
                            </form>
                        </div>

                        <div>
                            <label class="text-muted small">Số tin nhắn</label>
                            <div class="fw-medium">{{ $conversation->messages->count() }}</div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('admin.chat.index') }}" class="btn btn-outline-secondary w-100">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
            </div>

            <!-- Main Chat Area -->
            <div class="col-md-9">
                <div class="card border-0 shadow-sm" style="height: 700px; display: flex; flex-direction: column;">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="mb-0">Nội dung hội thoại</h5>
                    </div>

                    <div class="card-body" style="flex: 1; overflow-y: auto;" id="chatMessages">
                        @forelse($conversation->messages as $message)
                            <div
                                class="mb-3 {{ $message->user_id == $conversation->benh_nhan_id ? 'text-start' : 'text-end' }}"
                                data-message-id="{{ $message->id }}">
                                <div class="d-inline-block" style="max-width: 70%;">
                                    <div class="small text-muted mb-1">
                                        <strong>{{ $message->user->name }}</strong>
                                        <span class="mx-1">•</span>
                                        {{ $message->created_at->format('H:i d/m/Y') }}
                                    </div>

                                    @if ($message->noi_dung)
                                        <div
                                            class="p-3 rounded {{ $message->user_id == $conversation->benh_nhan_id ? 'bg-light' : 'bg-primary text-white' }}">
                                            {{ $message->noi_dung }}
                                        </div>
                                    @endif

                                    @if ($message->hasAttachment())
                                        <div class="mt-2">
                                            @if ($message->isImage())
                                                <img src="{{ $message->file_url }}" alt="Attachment" class="img-thumbnail"
                                                    style="max-width: 300px;">
                                            @else
                                                <a href="{{ $message->file_url }}" target="_blank"
                                                    class="btn btn-sm btn-outline-secondary">
                                                    <i class="bi bi-file-earmark"></i> {{ $message->file_name }}
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                    @if ($message->is_read)
                                        <div class="small text-muted mt-1">
                                            <i class="bi bi-check2-all"></i> Đã đọc
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-chat-dots fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Chưa có tin nhắn nào</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                var lastMessageId = {{ $conversation->messages->last()->id ?? 0 }};
                var isLoadingMessages = false;

                // Auto scroll to bottom
                function scrollToBottom(smooth = false) {
                    var chatMessages = document.getElementById('chatMessages');
                    if (smooth) {
                        chatMessages.scrollTo({
                            top: chatMessages.scrollHeight,
                            behavior: 'smooth'
                        });
                    } else {
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }
                }
                scrollToBottom();

                // Function to load new messages
                function loadNewMessages() {
                    if (isLoadingMessages) return;
                    isLoadingMessages = true;

                    $.ajax({
                        url: '{{ route('admin.chat.messages', $conversation) }}',
                        method: 'GET',
                        success: function(messages) {
                            var hasNewMessages = false;

                            messages.forEach(function(message) {
                                if (message.id > lastMessageId) {
                                    // Bổ sung: Update lastMessageId TRƯỚC KHI append
                                    // Để tránh duplicate khi appendMessage return sớm
                                    lastMessageId = message.id;
                                    appendMessage(message);
                                    hasNewMessages = true;
                                }
                            });

                            if (hasNewMessages) {
                                scrollToBottom(true);
                            }
                            isLoadingMessages = false;
                        },
                        error: function() {
                            isLoadingMessages = false;
                        }
                    });
                }

                // Function to append message to chat
                function appendMessage(message) {
                    // Bổ sung: Check duplicate trước khi append
                    if ($('#chatMessages').find('[data-message-id="' + message.id + '"]').length > 0) {
                        return; // Tin nhắn đã tồn tại, bỏ qua
                    }

                    var isBenhNhan = message.user_id == {{ $conversation->benh_nhan_id }};
                    var alignClass = isBenhNhan ? 'text-start' : 'text-end';
                    var bgClass = isBenhNhan ? 'bg-light' : 'bg-primary text-white';
                    var userName = message.user ? message.user.name : 'User';
                    var time = new Date(message.created_at).toLocaleString('vi-VN', {
                        hour: '2-digit',
                        minute: '2-digit',
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric'
                    });

                    var messageHtml = '<div class="mb-3 ' + alignClass + '" data-message-id="' + message.id + '">';
                    messageHtml += '<div class="d-inline-block" style="max-width: 70%;">';
                    messageHtml += '<div class="small text-muted mb-1"><strong>' + userName + '</strong> <span class="mx-1">•</span> ' + time + '</div>';

                    if (message.noi_dung) {
                        messageHtml += '<div class="p-3 rounded ' + bgClass + '">' + escapeHtml(message.noi_dung) + '</div>';
                    }

                    if (message.file_path) {
                        var fileUrl = '{{ asset('storage') }}/' + message.file_path;
                        messageHtml += '<div class="mt-2">';
                        if (message.file_type === 'image') {
                            messageHtml += '<img src="' + fileUrl + '" class="img-thumbnail" style="max-width: 300px;">';
                        } else {
                            messageHtml += '<a href="' + fileUrl + '" target="_blank" class="btn btn-sm btn-outline-secondary">';
                            messageHtml += '<i class="bi bi-file-earmark"></i> ' + (message.file_name || 'File') + '</a>';
                        }
                        messageHtml += '</div>';
                    }

                    if (message.is_read) {
                        messageHtml += '<div class="small text-muted mt-1"><i class="bi bi-check2-all"></i> Đã đọc</div>';
                    }

                    messageHtml += '</div></div>';
                    $('#chatMessages').append(messageHtml);
                }

                // Helper function to escape HTML
                function escapeHtml(text) {
                    var map = {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    };
                    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
                }

                // Real-time polling every 500ms for admin monitoring
                setInterval(loadNewMessages, 500);
            });
        </script>
    @endpush
@endsection
