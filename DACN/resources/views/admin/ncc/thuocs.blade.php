@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-pills text-primary me-2"></i>
                Thuốc của NCC: {{ $ncc->ten }}
            </h2>
            <p class="text-muted mb-0">Quản lý danh mục thuốc và giá nhập mặc định</p>
        </div>
        <a href="{{ route('admin.ncc.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Thêm thuốc mới -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Thêm thuốc mới</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.ncc.thuocs.add', $ncc) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Chọn thuốc <span class="text-danger">*</span></label>
                            <select name="thuoc_id" class="form-select" required>
                                <option value="">-- Chọn thuốc --</option>
                                @foreach($allThuocs as $thuoc)
                                    @if(!$ncc->cungCapThuoc($thuoc->id))
                                        <option value="{{ $thuoc->id }}">
                                            {{ $thuoc->ten }}
                                            @if($thuoc->ham_luong) - {{ $thuoc->ham_luong }} @endif
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Giá nhập mặc định (VNĐ)</label>
                            <input type="number" name="gia_nhap_mac_dinh" class="form-control"
                                   min="0" step="0.01" placeholder="Ví dụ: 50000">
                            <small class="text-muted">Để trống nếu chưa có giá</small>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-plus me-1"></i> Thêm thuốc
                        </button>
                    </form>
                </div>
            </div>

            <!-- Thông tin NCC -->
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin NCC</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width: 40%">Email:</td>
                            <td>{{ $ncc->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">SĐT:</td>
                            <td>{{ $ncc->so_dien_thoai ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Địa chỉ:</td>
                            <td>{{ $ncc->dia_chi ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tổng thuốc:</td>
                            <td><strong class="text-primary">{{ $thuocsCuaNcc->count() }}</strong> loại</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Danh sách thuốc -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        Danh sách thuốc
                        <span class="badge bg-primary ms-2">{{ $thuocsCuaNcc->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($thuocsCuaNcc->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Chưa có thuốc nào. Thêm thuốc mới bên trái.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th style="width: 35%">Tên thuốc</th>
                                        <th style="width: 20%">Hàm lượng</th>
                                        <th style="width: 10%">Đơn vị</th>
                                        <th style="width: 20%">Giá nhập MĐ</th>
                                        <th style="width: 10%" class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($thuocsCuaNcc as $index => $thuoc)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $thuoc->ten }}</strong></td>
                                            <td>{{ $thuoc->ham_luong ?? 'N/A' }}</td>
                                            <td>{{ $thuoc->don_vi }}</td>
                                            <td>
                                                <form method="POST"
                                                      action="{{ route('admin.ncc.thuocs.update', [$ncc, $thuoc]) }}"
                                                      class="d-inline-flex align-items-center">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="number"
                                                           name="gia_nhap_mac_dinh"
                                                           class="form-control form-control-sm me-1"
                                                           style="width: 120px"
                                                           value="{{ $thuoc->pivot->gia_nhap_mac_dinh }}"
                                                           min="0" step="0.01"
                                                           placeholder="Giá">
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-success"
                                                            title="Lưu giá">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="text-center">
                                                <form method="POST"
                                                      action="{{ route('admin.ncc.thuocs.remove', [$ncc, $thuoc]) }}"
                                                      onsubmit="return confirm('Xóa thuốc này khỏi NCC?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Hướng dẫn -->
            <div class="alert alert-info mt-3">
                <h6 class="alert-heading"><i class="fas fa-lightbulb me-2"></i>Hướng dẫn sử dụng:</h6>
                <ul class="mb-0 small">
                    <li><strong>Thêm thuốc:</strong> Chọn thuốc từ dropdown bên trái, nhập giá nhập mặc định (nếu có), click "Thêm thuốc"</li>
                    <li><strong>Cập nhật giá:</strong> Sửa giá trong ô input, click nút <i class="fas fa-save text-success"></i> để lưu</li>
                    <li><strong>Xóa thuốc:</strong> Click nút <i class="fas fa-trash text-danger"></i> để xóa thuốc khỏi danh mục NCC</li>
                    <li><strong>Khi nhập kho:</strong> Chọn NCC → Chỉ hiển thị thuốc trong danh sách này + Tự điền giá mặc định</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
