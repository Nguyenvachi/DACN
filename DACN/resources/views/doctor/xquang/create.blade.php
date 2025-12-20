@extends('layouts.doctor')

@section('title', 'Chỉ định X-Quang')

@section('content')
<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-bold" style="color: #1f2937;">
                <i class="fas fa-x-ray me-2" style="color: #3b82f6;"></i>
                Chỉ định X-Quang
            </h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('doctor.benhan.edit', $benhAn->id) }}">Bệnh án #{{ $benhAn->id }}</a></li>
                    <li class="breadcrumb-item active">Chỉ định X-Quang</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('doctor.benhan.edit', $benhAn->id) }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Thông tin bệnh nhân --}}
            <div class="card vc-card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-user-injured me-2" style="color: #3b82f6;"></i>
                        Thông tin bệnh nhân
                    </h5>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Họ tên:</strong> {{ $benhAn->user->name }}</p>
                            <p class="mb-2"><strong>Mã BN:</strong> #{{ $benhAn->user->id }}</p>
                            <p class="mb-2"><strong>Ngày khám:</strong> {{ \Carbon\Carbon::parse($benhAn->ngay_kham)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Chẩn đoán:</strong> {{ $benhAn->chuan_doan ?? 'Chưa có' }}</p>
                            <p class="mb-2"><strong>Dịch vụ:</strong> {{ $benhAn->lichHen->dichVu->ten_dich_vu ?? 'N/A' }}</p>
                            <p class="mb-2"><strong>Trạng thái:</strong>
                                <span class="vc-badge vc-badge-{{ $benhAn->lichHen->trang_thai === 'Hoàn thành' ? 'success' : 'warning' }}">
                                    {{ $benhAn->lichHen->trang_thai }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form chỉ định --}}
            <div class="card vc-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-file-medical me-2" style="color: #10b981;"></i>
                        Thông tin X-Quang
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('doctor.xquang.store', $benhAn->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Loại X-Quang <span class="text-danger">*</span></label>
                            @if(isset($loaiXQuangs) && $loaiXQuangs->count() > 0)
                                <select name="loai_x_quang_id" class="form-select" required id="loaiXQuang">
                                    <option value="">-- Chọn loại X-Quang --</option>
                                    @foreach($loaiXQuangs as $it)
                                        <option value="{{ $it->id }}"
                                                data-gia="{{ $it->gia }}"
                                                data-time="{{ $it->thoi_gian_uoc_tinh }}"
                                                {{ (string) old('loai_x_quang_id') === (string) $it->id ? 'selected' : '' }}>
                                            {{ $it->ten }} - {{ number_format($it->gia ?? 0, 0, ',', '.') }}đ
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted" id="xqInfo"></small>
                                @error('loai_x_quang_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror

                                <script>
                                    (function () {
                                        const sel = document.getElementById('loaiXQuang');
                                        const info = document.getElementById('xqInfo');
                                        if (!sel || !info) return;

                                        const render = () => {
                                            const opt = sel.options[sel.selectedIndex];
                                            if (!opt || !opt.dataset) {
                                                info.textContent = '';
                                                return;
                                            }
                                            const gia = opt.dataset.gia;
                                            const time = opt.dataset.time;
                                            if (gia && time) {
                                                info.textContent = `Chi phí: ${parseInt(gia, 10).toLocaleString('vi-VN')}đ | Thời gian: ${time} phút`;
                                            } else {
                                                info.textContent = '';
                                            }
                                        };

                                        sel.addEventListener('change', render);
                                        render();
                                    })();
                                </script>
                            @else
                                <input type="text" name="loai" class="form-control" required value="{{ old('loai') }}" placeholder="Nhập loại X-Quang">
                                @error('loai')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Mô tả chi tiết</label>
                            <textarea name="mo_ta" class="form-control" rows="4"
                                      placeholder="VD: Chụp X-Quang phổi để đánh giá tổn thương...">{{ old('mo_ta') }}</textarea>
                            <small class="text-muted">Ghi rõ mục đích, yêu cầu đặc biệt nếu có</small>
                            @error('mo_ta')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('doctor.benhan.edit', $benhAn->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Hủy
                            </a>
                            <button type="submit" class="btn vc-btn-primary">
                                <i class="fas fa-check me-2"></i>Chỉ định X-Quang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            {{-- Danh sách X-Quang đã chỉ định --}}
            <div class="card vc-card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-list-alt me-2" style="color: #6366f1;"></i>
                        X-Quang đã chỉ định
                    </h5>
                </div>
                <div class="card-body">
                    @if($existing->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-x-ray fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có X-Quang nào</p>
                        </div>
                    @else
                        <div class="list-group list-group-flush">
                            @foreach($existing as $xq)
                                <div class="list-group-item px-0">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="mb-0">{{ $xq->loai }}</h6>
                                        @if($xq->trang_thai === 'completed')
                                            <span class="badge bg-success"><i class="fas fa-check me-1"></i>Đã có KQ</span>
                                        @elseif($xq->trang_thai === 'processing')
                                            <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Đang xử lý</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="fas fa-hourglass-start me-1"></i>Chờ thực hiện</span>
                                        @endif
                                    </div>

                                    @if($xq->mo_ta)
                                        <p class="mb-2 small text-muted">{{ Str::limit($xq->mo_ta, 80) }}</p>
                                    @endif

                                    <small class="text-muted">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($xq->created_at)->format('d/m/Y H:i') }}
                                    </small>

                                    <div class="mt-2">
                                        @if($xq->trang_thai === 'completed' && $xq->file_path)
                                            <a href="{{ route('doctor.xquang.download', $xq->id) }}" class="btn btn-sm btn-outline-primary me-1" target="_blank">
                                                <i class="fas fa-download me-1"></i>Tải KQ
                                            </a>
                                        @endif

                                        @if($xq->trang_thai !== 'completed')
                                            <form action="{{ route('doctor.xquang.destroy', $xq->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Xóa yêu cầu X-Quang này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash me-1"></i>Xóa
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Hướng dẫn --}}
            <div class="card vc-card mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle me-2" style="color: #10b981;"></i>
                        Lưu ý
                    </h6>
                    <ul class="mb-0 small">
                        <li>Chọn loại X-Quang phù hợp với chẩn đoán</li>
                        <li>Mô tả rõ mục đích để kỹ thuật viên thực hiện đúng</li>
                        <li>Kết quả sẽ được upload bởi kỹ thuật viên</li>
                        <li>Bạn có thể thêm nhận xét sau khi có kết quả</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
