@extends('layouts.admin')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">
                    <i class="bi bi-arrow-return-left text-warning"></i>
                    Hoàn tiền - {{ $hoaDon->ma_hoa_don }}
                </h2>
                <small class="text-muted">Tạo yêu cầu hoàn tiền cho hóa đơn</small>
            </div>
            <a href="{{ route('admin.hoadon.show', $hoaDon->id) }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Lỗi:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <!-- Form hoàn tiền -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0"><i class="bi bi-cash-stack"></i> Thông tin hoàn tiền</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.hoadon.refund.process', $hoaDon->id) }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="so_tien" class="form-label fw-bold">Số tiền hoàn <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('so_tien') is-invalid @enderror"
                                        id="so_tien" name="so_tien" step="0.01" min="0.01"
                                        max="{{ $hoaDon->so_tien_da_thanh_toan - $hoaDon->so_tien_da_hoan }}"
                                        value="{{ old('so_tien', $hoaDon->so_tien_da_thanh_toan - $hoaDon->so_tien_da_hoan) }}"
                                        required>
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                                <small class="text-muted">
                                    Tối đa:
                                    {{ number_format($hoaDon->so_tien_da_thanh_toan - $hoaDon->so_tien_da_hoan, 0, ',', '.') }}
                                    VNĐ
                                </small>
                                @error('so_tien')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="ly_do" class="form-label fw-bold">Lý do hoàn tiền <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('ly_do') is-invalid @enderror" id="ly_do" name="ly_do" rows="4"
                                    required>{{ old('ly_do') }}</textarea>
                                <small class="text-muted">Nhập lý do hoàn tiền chi tiết</small>
                                @error('ly_do')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phuong_thuc" class="form-label fw-bold">Phương thức hoàn tiền <span
                                        class="text-danger">*</span></label>
                                <select class="form-select @error('phuong_thuc') is-invalid @enderror" id="phuong_thuc"
                                    name="phuong_thuc" required>
                                    <option value="">-- Chọn phương thức --</option>
                                    <option value="tien_mat" {{ old('phuong_thuc') == 'tien_mat' ? 'selected' : '' }}>
                                        💵 Tiền mặt
                                    </option>
                                    <option value="chuyen_khoan"
                                        {{ old('phuong_thuc') == 'chuyen_khoan' ? 'selected' : '' }}>
                                        🏦 Chuyển khoản ngân hàng
                                    </option>
                                    <option value="hoan_cong" {{ old('phuong_thuc') == 'hoan_cong' ? 'selected' : '' }}>
                                        💳 Hoàn về cổng thanh toán gốc
                                    </option>
                                </select>
                                @error('phuong_thuc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-warning">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <strong>Lưu ý:</strong> Sau khi xác nhận hoàn tiền, hành động này không thể hoàn tác.
                                Vui lòng kiểm tra kỹ thông tin trước khi thực hiện.
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-circle"></i> Xác nhận hoàn tiền
                                </button>
                                <a href="{{ route('admin.hoadon.show', $hoaDon->id) }}" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Hủy
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Thông tin hóa đơn -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-receipt"></i> Thông tin hóa đơn</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <tr>
                                <td class="fw-bold">Mã HĐ:</td>
                                <td>{{ $hoaDon->ma_hoa_don }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Bệnh nhân:</td>
                                <td>{{ $hoaDon->user->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Tổng tiền:</td>
                                <td class="text-end">{{ number_format($hoaDon->tong_tien, 0, ',', '.') }} VNĐ</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Đã thanh toán:</td>
                                <td class="text-end text-success">
                                    {{ number_format($hoaDon->so_tien_da_thanh_toan, 0, ',', '.') }} VNĐ</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Đã hoàn:</td>
                                <td class="text-end text-warning">
                                    {{ number_format($hoaDon->so_tien_da_hoan, 0, ',', '.') }} VNĐ</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Có thể hoàn:</td>
                                <td class="text-end text-primary fw-bold">
                                    {{ number_format($hoaDon->so_tien_da_thanh_toan - $hoaDon->so_tien_da_hoan, 0, ',', '.') }}
                                    VNĐ
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Trạng thái:</td>
                                <td>
                                    <span
                                        class="badge
                                        @if ($hoaDon->status == 'paid') bg-success
                                        @elseif($hoaDon->status == 'partial') bg-warning
                                        @elseif($hoaDon->status == 'refunded') bg-info
                                        @else bg-secondary @endif">
                                        {{ $hoaDon->trang_thai }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Lịch sử hoàn tiền -->
                @if ($hoaDon->hoanTiens->count() > 0)
                    <div class="card mt-3">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0"><i class="bi bi-clock-history"></i> Lịch sử hoàn tiền</h6>
                        </div>
                        <div class="card-body">
                            @foreach ($hoaDon->hoanTiens as $ht)
                                <div class="mb-2 pb-2 border-bottom">
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">{{ $ht->created_at->format('d/m/Y H:i') }}</small>
                                        <strong class="text-success">{{ number_format($ht->so_tien, 0, ',', '.') }}
                                            VNĐ</strong>
                                    </div>
                                    <small class="text-muted">{{ $ht->ly_do }}</small>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
