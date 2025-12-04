@extends('layouts.admin')

@section('content')

    <div class="container-fluid py-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-envelope-check text-primary me-2"></i>
                Test gửi Mail Nhắc Lịch Hẹn
            </h2>

            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Quay lại Dashboard
            </a>
        </div>

        {{-- MAIN CARD --}}
        <div class="card shadow-sm border-0">
            <div class="card-body">

                {{-- SUCCESS --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- ERROR --}}
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                        <button class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- TABLE --}}
                @if (isset($lichHenList) && $lichHenList->count() > 0)
                    <h5 class="text-muted mb-3">Danh sách lịch hẹn có thể test:</h5>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#ID</th>
                                    <th>Bệnh nhân</th>
                                    <th>Email</th>
                                    <th>Bác sĩ</th>
                                    <th>Ngày & Giờ</th>
                                    <th>Trạng thái</th>
                                    <th class="text-center">Thao tác</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($lichHenList as $lh)
                                    <tr>
                                        <td><strong>#{{ $lh->id }}</strong></td>

                                        <td>{{ optional($lh->user)->name ?? 'N/A' }}</td>

                                        <td>
                                            @if ($lh->user && $lh->user->email)
                                                <span class="badge bg-success">{{ $lh->user->email }}</span>
                                            @else
                                                <span class="badge bg-danger">Không có email</span>
                                            @endif
                                        </td>

                                        <td>{{ optional($lh->bacSi)->ho_ten ?? 'N/A' }}</td>

                                        <td>
                                            <small>{{ $lh->ngay_hen }}</small> <br>
                                            <strong>{{ $lh->thoi_gian_hen }}</strong>
                                        </td>

                                        <td>
                                            <span
                                                class="badge
                                            @if ($lh->trang_thai == 'Đã xác nhận') bg-success
                                            @elseif($lh->trang_thai == 'Chờ xác nhận') bg-warning
                                            @else bg-secondary @endif">
                                                {{ $lh->trang_thai }}
                                            </span>
                                        </td>

                                        <td class="text-center">
                                            @if ($lh->user && $lh->user->email)
                                                <form method="POST"
                                                    action="{{ route('admin.tools.test-mail.send', $lh->id) }}"
                                                    class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-primary">
                                                        <i class="bi bi-send"></i> Gửi Mail
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-sm btn-secondary" disabled>
                                                    <i class="bi bi-x-circle"></i> Không gửi được
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                    <hr>

                    {{-- HƯỚNG DẪN --}}
                    <div class="alert alert-info">
                        <h6 class="fw-bold"><i class="bi bi-info-circle me-1"></i> Hướng dẫn:</h6>
                        <ul class="mb-0">
                            <li>Chọn một lịch hẹn có email hợp lệ.</li>
                            <li>Bấm nút <strong>“Gửi Mail”</strong>.</li>
                            <li>Kiểm tra hộp thư <strong>{{ config('mail.from.address') }}</strong>.</li>
                            <li>Email test sẽ có nhãn <strong>(T-TEST)</strong>.</li>
                        </ul>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        Không tìm thấy lịch hẹn nào có email hợp lệ để test.
                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection
