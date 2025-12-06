@extends('layouts.patient-modern')

@section('content')
    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">
                <i class="bi bi-star-fill text-warning me-2"></i>
                Đánh giá của tôi
            </h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Bác sĩ</th>
                                <th>Đánh giá</th>
                                <th>Nội dung</th>
                                <th>Trạng thái</th>
                                <th>Ngày</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($danhGias as $danhGia)
                                <tr>
                                    <td>
                                        <strong>{{ $danhGia->bacSi->ho_ten }}</strong>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $danhGia->rating)
                                                    <i class="bi bi-star-fill text-warning"></i>
                                                @else
                                                    <i class="bi bi-star text-muted"></i>
                                                @endif
                                            @endfor
                                            <span class="ms-2">{{ $danhGia->rating }}/5</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="max-width: 300px;">
                                            {{ Str::limit($danhGia->noi_dung, 80) }}
                                        </div>
                                    </td>
                                    <td>
                                        @if ($danhGia->trang_thai === 'approved')
                                            <span class="badge bg-success">Đã duyệt</span>
                                        @elseif ($danhGia->trang_thai === 'pending')
                                            <span class="badge bg-warning text-dark">Chờ duyệt</span>
                                        @else
                                            <span class="badge bg-danger">Từ chối</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $danhGia->created_at->format('d/m/Y') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('patient.danhgia.edit', $danhGia->id) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('patient.danhgia.destroy', $danhGia->id) }}"
                                                method="POST" class="d-inline"
                                                onsubmit="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" type="submit">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <i class="bi bi-inbox fs-1 text-muted"></i>
                                        <p class="text-muted mt-2">Bạn chưa có đánh giá nào</p>
                                        <a href="{{ route('lichhen.my') }}" class="btn btn-primary">
                                            Xem lịch hẹn của tôi
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($danhGias->hasPages())
                <div class="card-footer bg-white">
                    {{ $danhGias->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
