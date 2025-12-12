@php
    $role = auth()->user()->role ?? 'patient';

    // Mỗi role dùng layout riêng — mapping rõ ràng
    $layout = match ($role) {
        'admin' => 'layouts.admin',
        'doctor' => 'layouts.doctor',
        'staff' => 'layouts.staff',
        'patient' => 'layouts.patient-modern',
        default => 'layouts.app',
    };
@endphp

@extends($layout)

@section('content')
    <div class="container-fluid py-4">

        {{-- TIÊU ĐỀ + LỊCH SỬ --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">
                <i class="bi bi-journal-medical text-primary"></i>
                Chi tiết bệnh án
            </h3>

            @if (in_array($role, ['admin', 'doctor']))
                <a href="{{ route($role . '.benhan.audit', $record) }}"
                    class="btn btn-secondary d-flex align-items-center gap-2">
                    <i class="bi bi-clock-history"></i> Lịch sử thay đổi
                </a>
            @endif
        </div>


        {{-- ===========================
        THÔNG TIN CƠ BẢN
    ============================ --}}
        <div class="card shadow-sm mb-4 border-start border-primary border-3">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">
                    <h5 class="fw-semibold">
                        <i class="bi bi-info-circle"></i> Thông tin cơ bản
                    </h5>

                    @if (in_array($role, ['admin', 'doctor']))
                        <a href="{{ route($role . '.benhan.edit', $record) }}"
                            class="btn btn-sm btn-primary d-flex align-items-center gap-1">
                            <i class="bi bi-pencil"></i> Sửa
                        </a>
                    @endif
                </div>

                <div class="row g-3">
                    <div class="col-md-6"><strong>Tiêu đề:</strong> {{ $record->tieu_de }}</div>
                    <div class="col-md-6"><strong>Ngày khám:</strong> {{ $record->ngay_kham->format('d/m/Y') }}</div>
                    <div class="col-md-6"><strong>Bệnh nhân:</strong> {{ $record->benhNhan->name ?? $record->user_id }}
                    </div>
                    <div class="col-md-6"><strong>Bác sĩ:</strong> {{ $record->bacSi->ho_ten ?? $record->bac_si_id }}</div>
                </div>

            </div>
        </div>


        {{-- ===========================
        THÔNG TIN LÂM SÀNG
    ============================ --}}
        <div class="card shadow-sm mb-4 border-start border-success border-3">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">
                    <h5 class="fw-semibold">
                        <i class="bi bi-clipboard2-pulse"></i> Thông tin lâm sàng
                    </h5>

                    @if (in_array($role, ['admin', 'doctor']))
                        <a href="{{ route($role . '.benhan.edit', $record) }}"
                            class="btn btn-sm btn-primary d-flex align-items-center gap-1">
                            <i class="bi bi-pencil"></i> Sửa
                        </a>
                    @endif
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <h6 class="fw-semibold">Triệu chứng</h6>
                        <p>{{ $record->trieu_chung }}</p>
                    </div>

                    <div class="col-md-4">
                        <h6 class="fw-semibold">Chẩn đoán</h6>
                        <p>{{ $record->chuan_doan }}</p>
                    </div>

                    <div class="col-md-4">
                        <h6 class="fw-semibold">Điều trị</h6>
                        <p>{{ $record->dieu_tri }}</p>
                    </div>
                </div>

            </div>
        </div>


        {{-- ===========================
        UPLOAD FILE (ADMIN/DOCTOR)
    ============================ --}}
        @if (in_array($role, ['admin', 'doctor']))
            <div class="card shadow-sm mb-4 border-start border-warning border-3">
                <div class="card-body">

                    <h5 class="fw-semibold mb-3">
                        <i class="bi bi-upload"></i> Tải lên tệp đính kèm
                    </h5>

                    <form action="{{ route($role . '.benhan.files.upload', $record) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <input type="file" name="files[]" multiple class="form-control mb-3">

                        <button class="btn btn-primary">
                            <i class="bi bi-cloud-arrow-up"></i> Tải lên
                        </button>
                    </form>

                </div>
            </div>
        @endif


        {{-- ===========================
        DANH SÁCH FILE
    ============================ --}}
        <div class="card shadow-sm mb-4 border-start border-purple border-3" style="border-color:#6f42c1!important">
            <div class="card-body">

                <h5 class="fw-semibold mb-3">
                    <i class="bi bi-paperclip"></i> Tệp đính kèm
                </h5>

                <ul class="list-unstyled">
                    @forelse($record->files as $f)
                        <li class="mb-2 d-flex align-items-center gap-3">

                            @if ($f->disk_name === 'benh_an_private')
                                <a class="text-primary"
                                    href="{{ URL::temporarySignedRoute($role . '.benhan.files.download', now()->addMinutes(10), ['file' => $f->id]) }}">
                                    {{ $f->ten_file ?? basename($f->path) }}
                                </a>
                            @else
                                <a class="text-primary" href="{{ url(Storage::url($f->path)) }}" target="_blank">
                                    {{ $f->ten_file ?? basename($f->path) }}
                                </a>
                            @endif

                            @if (in_array($role, ['admin', 'doctor']))
                                <form method="POST" class="d-inline"
                                    action="{{ route($role . '.benhan.files.destroy', [$record, $f]) }}"
                                    onsubmit="return confirm('Xác nhận xóa file này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Xóa</button>
                                </form>
                            @endif
                        </li>
                    @empty
                        <li class="text-muted">Chưa có tệp đính kèm nào.</li>
                    @endforelse
                </ul>

            </div>
        </div>


        {{-- ===========================
        ĐƠN THUỐC
    ============================ --}}
        <div class="card shadow-sm mb-4 border-start border-danger border-3">
            <div class="card-body">

                <h5 class="fw-semibold mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-capsule"></i> Đơn thuốc
                </h5>

                <ul class="ms-4">
                    @forelse($benhAn->donThuocs as $dt)
                        <li class="mb-2">
                            <strong>Đơn #{{ $dt->id }}</strong>
                            — {{ $dt->created_at->format('d/m/Y') }}

                            @if ($dt->ghi_chu)
                                <span class="text-muted">({{ $dt->ghi_chu }})</span>
                            @endif

                            <ul class="ms-4 mt-1">
                                @foreach ($dt->items as $it)
                                    <li>
                                        {{ $it->thuoc->ten ?? 'Thuốc #' . $it->thuoc_id }}
                                        — SL: {{ $it->so_luong }}
                                        @if ($it->lieu_dung)
                                            | Liều: {{ $it->lieu_dung }}
                                        @endif
                                        @if ($it->cach_dung)
                                            | Cách: {{ $it->cach_dung }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @empty
                        <li class="text-muted">Chưa có đơn thuốc nào.</li>
                    @endforelse
                </ul>

                @if (in_array($role, ['admin', 'doctor']))
                    <a class="btn btn-outline-primary btn-sm mt-3" href="{{ route('benhan.donthuoc.create', $benhAn) }}">
                        + Kê đơn thuốc
                    </a>
                @endif

            </div>
        </div>


        {{-- ===========================
        KẾT QUẢ XÉT NGHIỆM
    ============================ --}}
        <div class="card shadow-sm mb-4 border-start border-info border-3">
            <div class="card-body">

                <h5 class="fw-semibold mb-3">
                    <i class="bi bi-diagram-2"></i> Kết quả xét nghiệm
                </h5>

                {{-- Form upload --}}
                @if (in_array($role, ['admin', 'doctor']))
                    <form class="row g-3 mb-3" method="POST" action="{{ route('benhan.xetnghiem.upload', $benhAn) }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="col-md-3">
                            <input name="loai" class="form-control" placeholder="Loại xét nghiệm" required>
                        </div>

                        <div class="col-md-5">
                            <input type="file" name="file" class="form-control" required>
                        </div>

                        <div class="col-md-3">
                            <input name="mo_ta" class="form-control" placeholder="Mô tả (tuỳ chọn)">
                        </div>

                        <div class="col-md-1">
                            <button class="btn btn-primary w-100">Tải</button>
                        </div>
                    </form>
                @endif

                {{-- danh sách --}}
                <ul class="ms-3">
                    @forelse($benhAn->xetNghiems as $xn)
                        <li class="mb-2">
                            {{ $xn->loai }} —
                            <a class="text-primary"
                                href="{{ URL::temporarySignedRoute($role . '.benhan.xetnghiem.download', now()->addMinutes(10), ['xetNghiem' => $xn->id]) }}">
                                Xem file
                            </a>

                            @if ($xn->mo_ta)
                                <span class="text-muted"> — {{ $xn->mo_ta }}</span>
                            @endif
                        </li>

                    @empty
                        <li>Chưa có kết quả xét nghiệm</li>
                    @endforelse
                </ul>

            </div>
        </div>

        {{-- ===========================
        SIÊU ÂM
    ============================ --}}
        @if ($role === 'doctor' && isset($benhAn->sieuAms))
        <div class="card shadow-sm mb-4 border-start border-success border-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold mb-0">
                        <i class="bi bi-display"></i> Siêu âm
                    </h5>
                    <a href="{{ route('doctor.sieu-am.create', $benhAn) }}" class="btn btn-sm btn-success">
                        <i class="bi bi-plus-lg"></i> Chỉ định siêu âm
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Loại siêu âm</th>
                                <th>Ngày chỉ định</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($benhAn->sieuAms as $sa)
                                <tr>
                                    <td>{{ $sa->loai_sieu_am }}</td>
                                    <td>{{ \Carbon\Carbon::parse($sa->ngay_chi_dinh)->format('d/m/Y') }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($sa->trang_thai) {
                                                'Chờ thực hiện' => 'bg-warning',
                                                'Đang thực hiện' => 'bg-info',
                                                'Hoàn thành' => 'bg-success',
                                                'Đã hủy' => 'bg-secondary',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $sa->trang_thai }}</span>
                                    </td>
                                    <td>
                                        @if ($sa->trang_thai !== 'Hoàn thành' && $sa->trang_thai !== 'Đã hủy')
                                            <a href="{{ route('doctor.sieu-am.edit', $sa) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i> Nhập kết quả
                                            </a>
                                        @else
                                            <a href="{{ route('doctor.sieu-am.edit', $sa) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Xem kết quả
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Chưa có chỉ định siêu âm</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- ===========================
        THỦ THUẬT
    ============================ --}}
        @if ($role === 'doctor' && isset($benhAn->thuThuats))
        <div class="card shadow-sm mb-4 border-start border-warning border-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold mb-0">
                        <i class="bi bi-clipboard-pulse"></i> Thủ thuật
                    </h5>
                    <a href="{{ route('doctor.thu-thuat.create', $benhAn) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-plus-lg"></i> Chỉ định thủ thuật
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tên thủ thuật</th>
                                <th>Ngày chỉ định</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($benhAn->thuThuats as $tt)
                                <tr>
                                    <td>{{ $tt->ten_thu_thuat }}</td>
                                    <td>{{ \Carbon\Carbon::parse($tt->ngay_chi_dinh)->format('d/m/Y') }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($tt->trang_thai) {
                                                'Chờ thực hiện' => 'bg-warning',
                                                'Đang thực hiện' => 'bg-info',
                                                'Hoàn thành' => 'bg-success',
                                                'Đã hủy' => 'bg-secondary',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $tt->trang_thai }}</span>
                                    </td>
                                    <td>
                                        @if ($tt->trang_thai !== 'Hoàn thành' && $tt->trang_thai !== 'Đã hủy')
                                            <a href="{{ route('doctor.thu-thuat.edit', $tt) }}" class="btn btn-sm btn-primary">
                                                <i class="bi bi-pencil"></i> Nhập kết quả
                                            </a>
                                        @else
                                            <a href="{{ route('doctor.thu-thuat.edit', $tt) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Xem kết quả
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Chưa có chỉ định thủ thuật</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        {{-- ===========================
        LỊCH TÁI KHÁM
    ============================ --}}
        @if ($role === 'doctor')
        <div class="card shadow-sm mb-4 border-start border-primary border-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-semibold mb-0">
                        <i class="bi bi-calendar-check"></i> Lịch tái khám
                    </h5>
                    <a href="{{ route('doctor.lich-tai-kham.create', ['benh_an_id' => $benhAn->id]) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-lg"></i> Tạo lịch tái khám
                    </a>
                </div>

                @if(isset($benhAn->lichTaiKhams) && $benhAn->lichTaiKhams->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Ngày hẹn</th>
                                    <th>Lý do</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($benhAn->lichTaiKhams as $ltk)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($ltk->ngay_hen)->format('d/m/Y') }}</td>
                                        <td>{{ Str::limit($ltk->ly_do, 50) }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match($ltk->trang_thai) {
                                                    'Đã hẹn' => 'bg-info',
                                                    'Đã xác nhận' => 'bg-primary',
                                                    'Đã khám' => 'bg-success',
                                                    'Đã hủy' => 'bg-secondary',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $ltk->trang_thai }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('doctor.lich-tai-kham.show', $ltk) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Chi tiết
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">Chưa có lịch tái khám</p>
                @endif
            </div>
        </div>
        @endif

    </div>
@endsection
