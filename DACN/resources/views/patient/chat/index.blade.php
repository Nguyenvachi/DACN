@extends('layouts.patient-modern')

@section('title', 'Tin nhắn tư vấn')
@section('page-title', 'Hộp thư tư vấn')
@section('page-subtitle', 'Trao đổi trực tiếp với bác sĩ của bạn')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                {{-- INBOX CARD --}}
                <div class="card shadow-sm border-0 overflow-hidden" style="min-height: 600px;">
                    {{-- Header & Search --}}
                    <div class="card-header bg-white p-4 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-chat-square-text-fill text-primary me-2"></i>Tin nhắn gần
                                đây</h5>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">
                                {{ $conversations->count() }} cuộc trò chuyện
                            </span>
                        </div>
                        <div class="position-relative">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input type="text" class="form-control ps-5 bg-light border-0"
                                placeholder="Tìm kiếm bác sĩ hoặc nội dung...">
                        </div>
                    </div>

                    {{-- Conversation List --}}
                    <div class="list-group list-group-flush overflow-auto" style="max-height: 600px;">
                        @forelse($conversations as $conversation)
                            @php
                                $unreadCount = $conversation->getUnreadCountForUser(auth()->id());
                                $isUnread = $unreadCount > 0;
                                $doctor = $conversation->bacSi;
                            @endphp

                            <a href="{{ route('patient.chat.show', $conversation) }}"
                                class="list-group-item list-group-item-action p-3 border-bottom transition-bg {{ $isUnread ? 'bg-blue-50' : '' }}">
                                <div class="d-flex align-items-center">
                                    {{-- Avatar --}}
                                    <div class="position-relative me-3">
                                        @if (optional($doctor)->avatar)
                                            <img src="{{ asset('storage/' . $doctor->avatar) }}"
                                                class="rounded-circle object-fit-cover shadow-sm" width="56"
                                                height="56" alt="{{ optional($doctor)->ho_ten }}">
                                        @else
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center fw-bold fs-5"
                                                style="width: 56px; height: 56px;">
                                                {{ strtoupper(substr(optional($doctor)->ho_ten ?? 'B', 0, 1)) }}
                                            </div>
                                        @endif

                                        {{-- Online/Status Dot (Optional) --}}
                                        <span
                                            class="position-absolute bottom-0 end-0 p-1 bg-success border border-white rounded-circle"></span>
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="d-flex justify-content-between align-items-baseline mb-1">
                                            <h6
                                                class="mb-0 text-truncate {{ $isUnread ? 'fw-bold text-dark' : 'text-secondary fw-semibold' }}">
                                                {{ optional($doctor)->ho_ten ?? 'Bác sĩ hỗ trợ' }}
                                            </h6>
                                            <small class="text-muted ms-2 flex-shrink-0" style="font-size: 0.75rem;">
                                                {{ $conversation->last_message_at->diffForHumans() }}
                                            </small>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="mb-0 text-truncate text-muted small pe-3" style="max-width: 80%;">
                                                @if ($conversation->latestMessage)
                                                    @if ($conversation->latestMessage->user_id == auth()->id())
                                                        <span class="text-secondary">Bạn:</span>
                                                    @endif
                                                    <span class="{{ $isUnread ? 'fw-semibold text-dark' : '' }}">
                                                        {{ $conversation->latestMessage->noi_dung }}
                                                    </span>
                                                @else
                                                    <span class="fst-italic">Bắt đầu cuộc trò chuyện mới...</span>
                                                @endif
                                            </p>

                                            @if ($unreadCount > 0)
                                                <span
                                                    class="badge bg-danger rounded-pill shadow-sm">{{ $unreadCount }}</span>
                                            @else
                                                <i class="bi bi-chevron-right text-muted small opacity-50"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle"
                                        style="width: 80px; height: 80px;">
                                        <i class="bi bi-chat-square-dots text-muted fs-1 opacity-50"></i>
                                    </div>
                                </div>
                                <h6 class="text-muted fw-bold">Chưa có tin nhắn nào</h6>
                                <p class="text-muted small mb-4">Hãy đặt lịch khám để bắt đầu trò chuyện với bác sĩ.</p>
                                <a href="{{ route('public.bacsi.index') }}" class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-calendar-plus me-2"></i>Tìm bác sĩ
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .bg-blue-50 {
                background-color: #f0f7ff;
            }

            .transition-bg {
                transition: background-color 0.2s ease;
            }

            .transition-bg:hover {
                background-color: #f8f9fa;
            }

            /* Tùy chỉnh thanh cuộn cho đẹp */
            .list-group::-webkit-scrollbar {
                width: 6px;
            }

            .list-group::-webkit-scrollbar-thumb {
                background-color: #dee2e6;
                border-radius: 4px;
            }
        </style>
    @endpush
@endsection
