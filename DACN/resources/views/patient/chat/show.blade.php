@extends('layouts.patient-modern')

@section('title', 'Chat với bác sĩ')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card border-0 shadow-sm" style="height: 700px; display: flex; flex-direction: column;">
                    <!-- Chat Header -->
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('patient.chat.index') }}" class="text-white me-3">
                                    <i class="bi bi-arrow-left fs-5"></i>
                                </a>
                                @if ($conversation->bacSi->avatar)
                                    <img src="{{ asset('storage/' . $conversation->bacSi->avatar) }}"
                                        alt="{{ $conversation->bacSi->ho_ten }}" class="rounded-circle me-3"
                                        style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center me-3"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-0">{{ $conversation->bacSi->ho_ten }}</h5>
                                    <small>{{ $conversation->bacSi->chuyen_khoa }}</small>
                                </div>
                            </div>
                            <div>
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-circle-fill text-success" style="font-size: 8px;"></i>
                                    {{ $conversation->trang_thai }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div class="card-body bg-light" style="flex: 1; overflow-y: auto;" id="chatMessages">
                        @forelse($conversation->messages as $message)
                            <div class="mb-3 d-flex {{ $message->user_id == auth()->id() ? 'justify-content-end' : 'justify-content-start' }}"
                                data-message-id="{{ $message->id }}">
                                <div style="max-width: 70%;">
                                    @if ($message->user_id != auth()->id())
                                        <div class="small text-muted mb-1">
                                            <strong>{{ $message->user->name }}</strong>
                                        </div>
                                    @endif

                                    @if ($message->noi_dung)
                                        <div
                                            class="p-3 rounded {{ $message->user_id == auth()->id() ? 'bg-primary text-white' : 'bg-white' }}">
                                            {{ $message->noi_dung }}
                                        </div>
                                    @endif

                                    @if ($message->hasAttachment())
                                        <div class="mt-2">
                                            @if ($message->isImage())
                                                <img src="{{ $message->file_url }}" alt="Attachment"
                                                    class="chat-msg-img img-thumbnail" style="max-width: 300px;">
                                            @else
                                                <a href="{{ $message->file_url }}" target="_blank"
                                                    class="btn btn-sm {{ $message->user_id == auth()->id() ? 'btn-light' : 'btn-outline-secondary' }}">
                                                    <i class="bi bi-file-earmark"></i> {{ $message->file_name }}
                                                </a>
                                            @endif
                                        </div>
                                    @endif

                                    <div
                                        class="small text-muted mt-1 {{ $message->user_id == auth()->id() ? 'text-end' : '' }}">
                                        {{ $message->created_at->format('H:i') }}
                                        @if ($message->user_id == auth()->id() && $message->is_read)
                                            <i class="bi bi-check2-all text-primary"></i>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-chat-dots fs-1 text-muted"></i>
                                <p class="text-muted mt-2">Bắt đầu cuộc trò chuyện</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Message Input -->
                    @if ($conversation->trang_thai == 'Đang hoạt động')
                        <div class="card-footer bg-white border-top">
                            <form action="{{ route('patient.chat.send', $conversation) }}" method="POST"
                                enctype="multipart/form-data" id="messageForm">
                                @csrf
                                <div class="row g-2">
                                    <div class="col">
                                        <textarea name="noi_dung" class="form-control @error('noi_dung') is-invalid @enderror" rows="2"
                                            placeholder="Nhập tin nhắn..." id="messageInput"></textarea>
                                        @error('noi_dung')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-auto d-flex flex-column">
                                        <label for="fileInput" class="btn btn-outline-secondary mb-1" title="Đính kèm file">
                                            <i class="bi bi-paperclip"></i>
                                        </label>
                                        <input type="file" name="file" id="fileInput" class="d-none"
                                            accept=".jpg,.jpeg,.png,.pdf,.doc,.docx">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-send"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="filePreview" class="mt-2 small text-muted"></div>
                            </form>
                            <!-- Custom controls overlay (added) -->
                            <div class="custom-chat-controls" aria-hidden="true">
                                <button type="button" id="customAttachBtn" class="btn btn-light attach-btn"
                                    title="Đính kèm">
                                    <i class="bi bi-paperclip"></i>
                                </button>
                                <button type="button" id="customSendBtn" class="btn btn-success send-btn" title="Gửi">
                                    <i class="bi bi-send-fill text-white"></i>
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="card-footer bg-light text-center">
                            <p class="text-muted mb-0">Cuộc hội thoại đã
                                {{ $conversation->trang_thai == 'Đã đóng' ? 'đóng' : 'bị khóa' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <style>
            /* Khoảng đệm đáy để nội dung không bị che bởi footer (message input) */
            #chatMessages {
                padding-bottom: 140px;
            }

            /* Bubbles & media */
            .message-bubble {
                border-radius: 12px;
                padding: 0.75rem;
                word-break: break-word;
            }

            .chat-msg-img {
                max-width: 300px;
                height: auto;
                border-radius: 8px;
                display: block;
            }

            /* Thu nhỏ nút gửi / file để tránh tràn layout */
            #messageForm .btn {
                min-width: 44px;
            }

            /* File preview text */
            #filePreview {
                word-break: break-all;
                color: #6b7280;
            }

            @media (max-width: 768px) {
                #chatMessages {
                    padding-bottom: 180px;
                }

                .chat-msg-img {
                    max-width: 240px;
                }
            }

            /* Custom send/attach controls positioned inside footer */
            .card-footer {
                position: relative;
            }

            .custom-chat-controls {
                position: absolute;
                right: 16px;
                top: 8px;
                display: flex;
                gap: 8px;
                align-items: center;
            }

            .custom-chat-controls .attach-btn {
                width: 40px;
                height: 40px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .custom-chat-controls .send-btn {
                width: 44px;
                height: 44px;
                border-radius: 999px;
                display: flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
            }

            /* Hide original small buttons visually but keep them for progressive enhancement/form semantics */
            #messageForm .col-auto {
                opacity: 0;
                height: 0;
                overflow: hidden;
                position: absolute;
                right: 12px;
                top: 8px;
            }

            @media (min-width: 992px) {

                /* keep original mobile-friendly controls visible on very small screens */
                #messageForm .col-auto {
                    opacity: 1;
                    height: auto;
                    position: static;
                    overflow: visible;
                }

                .custom-chat-controls {
                    display: none;
                }
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                var lastMessageId = {{ $conversation->messages->last()->id ?? 0 }};
                var isLoadingMessages = false;
                var isSendingMessage = false;

                // Setup CSRF token for AJAX
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Auto scroll to bottom
                function scrollToBottom(smooth = false) {
                    var chatMessages = document.getElementById('chatMessages');
                    if (!chatMessages) return;
                    var footer = document.querySelector('.card-footer');
                    var footerHeight = footer ? footer.offsetHeight + 16 : 140;
                    var target = Math.max(0, chatMessages.scrollHeight - footerHeight);
                    if (smooth && chatMessages.scrollTo) {
                        chatMessages.scrollTo({
                            top: target,
                            behavior: 'smooth'
                        });
                    } else {
                        chatMessages.scrollTop = target;
                    }
                }
                scrollToBottom();

                // File preview
                $('#fileInput').change(function() {
                    if (this.files && this.files[0]) {
                        var fileName = this.files[0].name;
                        var fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
                        $('#filePreview').html('<i class="bi bi-file-earmark"></i> ' + fileName + ' (' +
                            fileSize + ' MB)');
                    }
                });

                // Custom controls bindings
                $('#customSendBtn').on('click', function() {
                    // Trigger existing form submit
                    $('#messageForm').submit();
                });

                $('#customAttachBtn').on('click', function() {
                    $('#fileInput').trigger('click');
                });

                // Intercept form submit to use AJAX instead
                $('#messageForm').on('submit', function(e) {
                    e.preventDefault();

                    if (isSendingMessage) return;
                    isSendingMessage = true;

                    var formData = new FormData(this);

                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            // Clear form
                            $('#messageInput').val('');
                            $('#fileInput').val('');
                            $('#filePreview').html('');

                            // Bổ sung: Không append ngay, để polling fetch sau 100ms
                            if (response.message) {
                                lastMessageId = response.message.id - 1; // Force fetch lại
                            }

                            setTimeout(function() {
                                isSendingMessage = false;
                                loadNewMessages(); // Fetch ngay tin nhắn mới
                            }, 100);
                        },
                        error: function(xhr) {
                            alert('Đã xảy ra lỗi khi gửi tin nhắn');
                            isSendingMessage = false;
                        }
                    });
                });

                // Enter to send (Shift+Enter for new line)
                $('#messageInput').keypress(function(e) {
                    if (e.which == 13 && !e.shiftKey) {
                        e.preventDefault();
                        $('#messageForm').submit();
                    }
                });

                // Function to load new messages
                function loadNewMessages() {
                    if (isLoadingMessages || isSendingMessage) return;
                    isLoadingMessages = true;

                    $.ajax({
                        url: '{{ route('patient.chat.messages', $conversation) }}',
                        method: 'GET',
                        success: function(messages) {
                            var hasNewMessages = false;

                            messages.forEach(function(message) {
                                if (message.id > lastMessageId) {
                                    // Bổ sung: Update lastMessageId TRƯỚC KHI append
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

                    var isMyMessage = message.user_id == {{ auth()->id() }};
                    var alignClass = isMyMessage ? 'justify-content-end' : 'justify-content-start';
                    var bgClass = isMyMessage ? 'bg-primary text-white' : 'bg-white';
                    var userName = message.user ? message.user.name : 'User';

                    var messageHtml = '<div class="mb-3 d-flex ' + alignClass + '" data-message-id="' + message.id +
                        '">';
                    messageHtml += '<div style="max-width: 70%;">';

                    if (!isMyMessage) {
                        messageHtml += '<div class="small text-muted mb-1"><strong>' + userName + '</strong></div>';
                    }

                    if (message.noi_dung) {
                        messageHtml += '<div class="p-3 rounded ' + bgClass + '">' + escapeHtml(message.noi_dung) +
                            '</div>';
                    }

                    if (message.file_path) {
                        var fileUrl = '{{ asset('storage') }}/' + message.file_path;
                        if (message.file_type === 'image') {
                            messageHtml += '<div class="mt-2"><img src="' + fileUrl +
                                '" class="img-thumbnail" style="max-width: 300px;"></div>';
                        } else {
                            messageHtml += '<div class="mt-2"><a href="' + fileUrl +
                                '" target="_blank" class="btn btn-sm ' + (isMyMessage ? 'btn-light' :
                                    'btn-outline-secondary') + '">';
                            messageHtml += '<i class="bi bi-file-earmark"></i> ' + (message.file_name || 'File') +
                                '</a></div>';
                        }
                    }

                    var time = new Date(message.created_at).toLocaleTimeString('vi-VN', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    messageHtml += '<div class="small text-muted mt-1 ' + (isMyMessage ? 'text-end' : '') + '">' + time;
                    if (isMyMessage && message.is_read) {
                        messageHtml += ' <i class="bi bi-check2-all text-primary"></i>';
                    }
                    messageHtml += '</div></div></div>';

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
                    return text.replace(/[&<>"']/g, function(m) {
                        return map[m];
                    });
                }

                // Bổ sung: Real-time polling tối ưu 500ms với check duplicate
                @if ($conversation->trang_thai == 'Đang hoạt động')
                    setInterval(loadNewMessages, 500);
                @endif
            });
        </script>
    @endpush
@endsection
