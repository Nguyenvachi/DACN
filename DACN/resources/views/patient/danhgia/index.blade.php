@extends('layouts.patient-modern')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0 text-primary">
                <i class="bi bi-star-fill text-warning me-2"></i>
                Đánh giá của tôi
            </h2>
            {{-- THÊM CODE: Nút tạo đánh giá mới nhanh hoặc thống kê nhỏ nếu cần sau này --}}
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow border-0 rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 custom-table">
                        <thead class="bg-light text-secondary">
                            <tr>
                                <th class="py-3 ps-4 text-uppercase small fw-bold">Bác sĩ</th>
                                <th class="py-3 text-uppercase small fw-bold">Đánh giá</th>
                                <th class="py-3 text-uppercase small fw-bold">Nội dung</th>
                                <th class="py-3 text-uppercase small fw-bold">Trạng thái</th>
                                <th class="py-3 text-uppercase small fw-bold">Ngày</th>
                                <th class="py-3 text-center text-uppercase small fw-bold">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($danhGias as $danhGia)
                                <tr>
                                    <td class="ps-4">
                                        {{-- THÊM CODE: Layout hiển thị Avatar + Tên --}}
                                        <div class="d-flex align-items-center">
                                            @if (isset($danhGia->bacSi->avatar) && $danhGia->bacSi->avatar)
                                                <img src="{{ asset('storage/' . $danhGia->bacSi->avatar) }}"
                                                    class="rounded-circle me-3 border" width="45" height="45"
                                                    style="object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3"
                                                    style="width: 45px; height: 45px;">
                                                    <span
                                                        class="fw-bold">{{ substr($danhGia->bacSi->ho_ten ?? 'BS', 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0 text-dark fw-bold">{{ $danhGia->bacSi->ho_ten }}</h6>
                                                <small
                                                    class="text-muted">{{ $danhGia->bacSi->chuyen_khoa ?? 'Chuyên khoa' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div
                                            class="d-flex align-items-center bg-light rounded-pill px-2 py-1 d-inline-flex">
                                            <span class="fw-bold text-warning me-1">{{ $danhGia->rating }}</span>
                                            <i class="bi bi-star-fill text-warning small me-1"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="max-width: 300px;" class="text-secondary">
                                            <i class="bi bi-quote text-muted me-1"></i>
                                            {{ Str::limit($danhGia->noi_dung, 60) }}
                                        </div>
                                    </td>
                                    <td>
                                        {{-- THÊM CODE: Soft badge style --}}
                                        @if ($danhGia->trang_thai === 'approved')
                                            <span
                                                class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                <i class="bi bi-check-circle me-1"></i> Đã duyệt
                                            </span>
                                        @elseif ($danhGia->trang_thai === 'pending')
                                            <span
                                                class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                                <i class="bi bi-hourglass-split me-1"></i> Chờ duyệt
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                                <i class="bi bi-x-circle me-1"></i> Từ chối
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted font-monospace">
                                            {{ $danhGia->created_at->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group shadow-sm rounded-3 overflow-hidden" role="group">
                                            <a href="{{ route('patient.danhgia.edit', $danhGia->id) }}"
                                                class="btn btn-sm btn-white text-warning border-end" title="Sửa đánh giá">
                                                <i class="bi bi-pencil-square fs-6"></i>
                                            </a>
                                            <form action="{{ route('patient.danhgia.destroy', $danhGia->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-white text-danger" type="submit"
                                                    title="Xóa đánh giá">
                                                    <i class="bi bi-trash fs-6"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <div class="py-4">
                                            <div class="mb-3">
                                                <i class="bi bi-stars fs-1 text-muted opacity-50"></i>
                                            </div>
                                            <h5 class="text-muted fw-normal">Bạn chưa có đánh giá nào</h5>
                                            <p class="text-muted small mb-4">Các đánh giá của bạn sẽ giúp cải thiện chất
                                                lượng dịch vụ.</p>
                                            <a href="{{ route('lichhen.my') }}"
                                                class="btn btn-primary rounded-pill px-4 shadow-sm">
                                                <i class="bi bi-calendar-check me-2"></i> Xem lịch hẹn để đánh giá
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($danhGias->hasPages())
                <div class="card-footer bg-white border-top-0 py-3">
                    {{ $danhGias->links() }}
                </div>
            @endif
        </div>

    </div>

    {{-- THÊM CODE: CSS tùy chỉnh cho trang này (Soft UI) --}}
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <style>
            .custom-table thead th {
                font-size: 0.8rem;
                letter-spacing: 0.5px;
                border-bottom: 2px solid #f0f2f5;
            }

            .custom-table tbody tr {
                transition: all 0.2s ease;
            }

            .custom-table tbody tr:hover {
                background-color: #f8f9fa;
                transform: translateY(-1px);
            }

            .btn-white {
                background-color: #fff;
                border: 1px solid #dee2e6;
                transition: all 0.2s;
            }

            .btn-white:hover {
                background-color: #f8f9fa;
                filter: brightness(0.95);
            }

            .badge {
                font-weight: 600;
            }
        </style>
    @endpush
@endsection
