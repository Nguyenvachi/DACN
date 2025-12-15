@extends('layouts.patient-modern')

@section('title', 'Lịch Hẹn Khám')

@section('content')
    <div class="container-fluid px-4 py-4">

        <!-- Page Header -->
        <x-patient.page-header title="Lịch Hẹn Khám" subtitle="Quản lý và theo dõi các lịch hẹn của bạn" icon="fa-calendar-alt"
            :action-route="route('public.bacsi.index')" action-text="Đặt lịch mới" action-icon="fa-plus" />

        <!-- Alerts -->
        @if (session('success'))
            <x-patient.alert type="success">{{ session('success') }}</x-patient.alert>
        @endif
        @if (session('error'))
            <x-patient.alert type="error">{{ session('error') }}</x-patient.alert>
        @endif

        <!-- Stats Overview -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <x-patient.stat-card title="Tổng lịch hẹn" :value="$danhSachLichHen->count()" icon="fa-calendar-check" color="primary" />
            </div>
            <div class="col-md-3">
                <x-patient.stat-card title="{{ \App\Models\LichHen::STATUS_PENDING_VN }}" :value="$danhSachLichHen->where('trang_thai', \App\Models\LichHen::STATUS_PENDING_VN)->count()" icon="fa-clock"
                    color="warning" />
            </div>
            <div class="col-md-3">
                <x-patient.stat-card title="{{ \App\Models\LichHen::STATUS_CONFIRMED_VN }}" :value="$danhSachLichHen->where('trang_thai', \App\Models\LichHen::STATUS_CONFIRMED_VN)->count()"
                    icon="fa-check-circle" color="success" />
            </div>
            <div class="col-md-3">
                <x-patient.stat-card title="{{ \App\Models\LichHen::STATUS_COMPLETED_VN }}" :value="$danhSachLichHen->where('trang_thai', \App\Models\LichHen::STATUS_COMPLETED_VN)->count()"
                    icon="fa-check-double" color="info" />
            </div>
        </div>

        @if ($danhSachLichHen->count() > 0)

            <!-- Filter Tabs -->
            <x-patient.filter-tabs :tabs="[
                ['id' => 'all', 'label' => 'Tất cả', 'icon' => 'fa-list', 'count' => $danhSachLichHen->count()],
                [
                    'id' => 'pending',
                    'label' => 'Chờ xác nhận',
                    'icon' => 'fa-clock',
                    'count' => $danhSachLichHen->where('trang_thai', \App\Models\LichHen::STATUS_PENDING_VN)->count(),
                ],
                [
                    'id' => 'confirmed',
                    'label' => 'Đã xác nhận',
                    'icon' => 'fa-check',
                    'count' => $danhSachLichHen->where('trang_thai', \App\Models\LichHen::STATUS_CONFIRMED_VN)->count(),
                ],
                [
                    'id' => 'completed',
                    'label' => \App\Models\LichHen::STATUS_COMPLETED_VN,
                    'icon' => 'fa-check-double',
                    'count' => $danhSachLichHen->where('trang_thai', \App\Models\LichHen::STATUS_COMPLETED_VN)->count(),
                ],
                [
                    'id' => 'cancelled',
                    'label' => 'Đã hủy',
                    'icon' => 'fa-times',
                    'count' => $danhSachLichHen->where('trang_thai', \App\Models\LichHen::STATUS_CANCELLED_VN)->count(),
                ],
            ]" />

            <!-- Listing -->
            <div class="card-hc">
                <div class="table-responsive">
                    <table id="appointmentsTable" class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4">Ngày & Giờ</th>
                                <th>Bác sĩ</th>
                                <th>Chuyên khoa</th>
                                <th>Dịch vụ</th>
                                <th>Trạng thái</th>
                                <th>Thanh toán</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($danhSachLichHen as $lichHen)
                                <tr data-status="{{ strtolower(str_replace(' ', '-', $lichHen->trang_thai)) }}">

                                    <!-- NGÀY GIỜ -->
                                    <td class="px-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded p-2 me-3">
                                                <i class="fas fa-calendar text-primary-hc"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">
                                                    {{ \Carbon\Carbon::parse($lichHen->ngay_hen)->format('d/m/Y') }}</div>
                                                <div class="text-secondary-hc small">
                                                    <i class="fas fa-clock"></i> {{ $lichHen->thoi_gian_hen }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- BÁC SĨ -->
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if ($lichHen->bacSi?->user?->avatar)
                                                <img src="{{ Storage::url($lichHen->bacSi->user->avatar) }}"
                                                    class="rounded-circle me-2"
                                                    style="width:40px;height:40px;object-fit:cover;">
                                            @else
                                                <div class="rounded-circle text-white d-flex align-items-center justify-content-center me-2"
                                                    style="width:40px;height:40px;background:linear-gradient(135deg,#667eea,#764ba2);">
                                                    <i class="fas fa-user-md"></i>
                                                </div>
                                            @endif
                                            <div class="fw-semibold">{{ $lichHen->bacSi->ho_ten ?? 'N/A' }}</div>
                                        </div>
                                    </td>

                                    <td><span class="badge bg-light text-dark border"><i
                                                class="fas fa-stethoscope me-1"></i>{{ $lichHen->bacSi->chuyen_khoa }}</span>
                                    </td>
                                    <td>{{ $lichHen->dichVu->ten_dich_vu }}</td>

                                    <!-- TRẠNG THÁI -->
                                    <td>
                                        <x-patient.status-badge :status="$lichHen->trang_thai" :type="match ($lichHen->trang_thai) {
                                            \App\Models\LichHen::STATUS_PENDING_VN => 'warning',
                                            \App\Models\LichHen::STATUS_CONFIRMED_VN => 'success',
                                            \App\Models\LichHen::STATUS_CANCELLED_VN => 'danger',
                                            \App\Models\LichHen::STATUS_COMPLETED_VN => 'info',
                                            default => 'default',
                                        }" />
                                    </td>

                                    <!-- THANH TOÁN -->
                                    <td>
                                        @if ($lichHen->is_paid)
                                            <span class="badge bg-success"><i class="fas fa-check-circle"></i> Đã TT</span>
                                        @else
                                            <span class="badge bg-warning text-dark"><i
                                                    class="fas fa-exclamation-circle"></i> Chưa TT</span>
                                        @endif
                                    </td>

                                    <!-- ACTIONS -->
                                    <td class="text-center">
                                        <div class="btn-group">

                                            <!-- 🔍 XEM CHI TIẾT (CHỈ 1 MODAL DUY NHẤT) -->
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-view-detail"
                                                data-bs-toggle="modal" data-bs-target="#detailModal"
                                                data-ngay="{{ \Carbon\Carbon::parse($lichHen->ngay_hen)->format('d/m/Y') }}"
                                                data-gio="{{ $lichHen->thoi_gian_hen }}"
                                                data-bacsi="{{ $lichHen->bacSi->ho_ten }}"
                                                data-khoa="{{ $lichHen->bacSi->chuyen_khoa }}"
                                                data-dichvu="{{ $lichHen->dichVu->ten_dich_vu }}"
                                                data-tien="{{ number_format($lichHen->tong_tien) }}đ"
                                                data-trangthai="{{ $lichHen->trang_thai }}"
                                                data-thanhtoan="{{ $lichHen->is_paid ? 'Đã thanh toán' : 'Chưa thanh toán' }}"
                                                data-ghichu="{{ $lichHen->ghi_chu }}">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- SỬA -->
                                            @if (in_array($lichHen->trang_thai, [\App\Models\LichHen::STATUS_PENDING_VN, \App\Models\LichHen::STATUS_CONFIRMED_VN]))
                                                <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                                                    data-bs-target="#editModal{{ $lichHen->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endif

                                            <!-- CHAT -->
                                            @if (in_array($lichHen->trang_thai, [
                                                    \App\Models\LichHen::STATUS_CONFIRMED_VN,
                                                    \App\Models\LichHen::STATUS_COMPLETED_VN,
                                                ]))
                                                <a href="{{ route('patient.chat.create', $lichHen->bac_si_id) }}"
                                                    class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-comment-medical"></i>
                                                </a>
                                            @endif

                                            {{-- ĐÁNH GIÁ: hiển thị khi lịch đã hoàn thành --}}
                                            @if ($lichHen->trang_thai === \App\Models\LichHen::STATUS_COMPLETED_VN)
                                                @php
                                                    $existingReview = \App\Models\DanhGia::where(
                                                        'lich_hen_id',
                                                        $lichHen->id,
                                                    )
                                                        ->where('user_id', auth()->id())
                                                        ->first();
                                                @endphp

                                                @if (!$existingReview)
                                                    <a href="{{ route('patient.danhgia.create') }}?lich_hen_id={{ $lichHen->id }}"
                                                        class="btn btn-sm btn-outline-success ms-1" title="Đánh giá bác sĩ">
                                                        <i class="fas fa-star"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('patient.danhgia.edit', $existingReview->id) }}"
                                                        class="btn btn-sm btn-outline-secondary ms-1"
                                                        title="Chỉnh sửa đánh giá">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                @endif
                                            @endif

                                            <!-- HỦY -->
                                            @if (in_array($lichHen->trang_thai, [\App\Models\LichHen::STATUS_PENDING_VN, \App\Models\LichHen::STATUS_CONFIRMED_VN]))
                                                <button class="btn btn-sm btn-outline-danger"
                                                    onclick="if(confirm('Xác nhận hủy lịch hẹn?')) document.getElementById('delete{{ $lichHen->id }}').submit();">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>

                                                <form id="delete{{ $lichHen->id }}" method="POST"
                                                    action="{{ route('patient.lichhen.destroy', $lichHen->id) }}"
                                                    style="display:none;">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @endif

                                        </div>
                                    </td>

                                </tr>

                                <!-- Modal SỬA GIỮ NGUYÊN NHƯ CŨ -->
                                @if (in_array($lichHen->trang_thai, [\App\Models\LichHen::STATUS_PENDING_VN, \App\Models\LichHen::STATUS_CONFIRMED_VN]))
                                    <div class="modal fade" id="editModal{{ $lichHen->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('lichhen.update', $lichHen->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-header bg-light">
                                                        <h5 class="modal-title">
                                                            <i class="fas fa-edit text-success"></i> Chỉnh sửa lịch hẹn
                                                        </h5>
                                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label>Ngày hẹn</label>
                                                            <input type="date" name="ngay_hen" class="form-control"
                                                                value="{{ $lichHen->ngay_hen }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Giờ hẹn</label>
                                                            <input type="time" name="thoi_gian_hen"
                                                                class="form-control"
                                                                value="{{ $lichHen->thoi_gian_hen }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label>Ghi chú</label>
                                                            <textarea name="ghi_chu" class="form-control" rows="3">{{ $lichHen->ghi_chu }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Hủy</button>
                                                        <button class="btn-hc-primary"><i class="fas fa-save"></i>
                                                            Lưu</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <x-patient.empty-state icon="fa-calendar-times" title="Chưa có lịch hẹn"
                description="Bạn chưa có lịch hẹn nào. Hãy đặt lịch ngay!" :action-route="route('public.bacsi.index')" action-text="Đặt lịch khám"
                action-icon="fa-calendar-plus" />

        @endif
    </div>



    <!-- 🟢🟢🟢 MODAL XEM CHI TIẾT DUY NHẤT (KHÔNG LAG, KHÔNG DUPLICATE) -->
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header bg-light">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle text-primary-hc"></i> Chi tiết lịch hẹn
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Ngày hẹn</label>
                            <div id="ctNgay" class="fw-semibold"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Giờ hẹn</label>
                            <div id="ctGio" class="fw-semibold"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Bác sĩ</label>
                            <div id="ctBacSi" class="fw-semibold"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Chuyên khoa</label>
                            <div id="ctKhoa" class="fw-semibold"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Dịch vụ</label>
                            <div id="ctDichVu" class="fw-semibold"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Tổng tiền</label>
                            <div id="ctTien" class="fw-semibold text-success"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Trạng thái</label>
                            <div id="ctTrangThai"></div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-secondary-hc">Thanh toán</label>
                            <div id="ctThanhToan"></div>
                        </div>

                        <div class="col-12">
                            <label class="small text-secondary-hc">Ghi chú</label>
                            <div id="ctGhiChu" class="p-3 bg-light rounded"></div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>

            </div>
        </div>
    </div>



    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                const detailModal = document.getElementById('detailModal');

                detailModal.addEventListener('show.bs.modal', function(event) {

                    const btn = event.relatedTarget;

                    document.getElementById("ctNgay").innerText = btn.dataset.ngay;
                    document.getElementById("ctGio").innerText = btn.dataset.gio;
                    document.getElementById("ctBacSi").innerText = btn.dataset.bacsi;
                    document.getElementById("ctKhoa").innerText = btn.dataset.khoa;
                    document.getElementById("ctDichVu").innerText = btn.dataset.dichvu;
                    document.getElementById("ctTien").innerText = btn.dataset.tien;

                    document.getElementById("ctTrangThai").innerHTML =
                        `<span class="badge bg-primary">${btn.dataset.trangthai}</span>`;

                    document.getElementById("ctThanhToan").innerHTML =
                        btn.dataset.thanhtoan === 'Đã thanh toán' ?
                        `<span class="badge bg-success">Đã thanh toán</span>` :
                        `<span class="badge bg-warning text-dark">Chưa thanh toán</span>`;

                    document.getElementById("ctGhiChu").innerText = btn.dataset.ghichu || "Không có ghi chú";
                });

            });
        </script>
    @endpush

@endsection
