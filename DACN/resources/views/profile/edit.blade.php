<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-800 leading-tight flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 01112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ __('Quản lý Hồ sơ cá nhân') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- TAB NAVIGATION -->
            <ul class="nav nav-tabs mb-4 bg-white rounded-lg shadow-sm p-2" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">
                        <i class="bi bi-person-circle me-2"></i>Thông tin tài khoản
                    </button>
                </li>
                @if(auth()->user()->isPatient())
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="medical-tab" data-bs-toggle="tab" data-bs-target="#medical" type="button" role="tab">
                        <i class="bi bi-heart-pulse me-2"></i>Hồ sơ y tế
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab">
                        <i class="bi bi-bell me-2"></i>Thông báo
                    </button>
                </li>
                @endif
            </ul>

            <!-- TAB CONTENT -->
            <div class="tab-content" id="profileTabsContent">

                <!-- TAB 1: ACCOUNT INFO -->
                <div class="tab-pane fade show active" id="account" role="tabpanel">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2">
                            <div class="p-6 sm:p-8 bg-white shadow-lg sm:rounded-xl border border-gray-100">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Thông tin cơ bản
                                </h3>
                                <div class="border-t border-gray-200 pt-4">
                                    @include('profile.partials.update-profile-information-form')
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-1 space-y-6">
                            <div class="p-6 sm:p-8 bg-white shadow-md sm:rounded-xl border border-yellow-300/40">
                                <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.1 0 2-.9 2-2V7a4 4 0 00-8 0v2c0 1.1.9 2 2 2h4zm-6 4h12a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4a2 2 0 012-2z" />
                                    </svg>
                                    Bảo mật tài khoản
                                </h3>
                                <div class="border-t border-gray-200 pt-4">
                                    @include('profile.partials.update-password-form')
                                </div>
                            </div>

                            <div class="p-6 sm:p-8 bg-white shadow-md sm:rounded-xl border border-red-400/40">
                                <h3 class="text-lg font-semibold text-red-600 mb-3 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.002 20h13.996c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.27 17c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    Khu vực nguy hiểm
                                </h3>
                                <p class="text-sm text-gray-600 mb-4">
                                    Thao tác này không thể hoàn tác. Hãy cân nhắc trước khi xóa tài khoản.
                                </p>
                                <div class="border-t border-gray-200 pt-4">
                                    @include('profile.partials.delete-user-form')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(auth()->user()->isPatient())
                <!-- TAB 2: MEDICAL PROFILE -->
                <div class="tab-pane fade" id="medical" role="tabpanel">
                    <div class="row">
                        <!-- Avatar Upload -->
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body text-center">
                                    <h5 class="card-title mb-3"><i class="bi bi-person-bounding-box me-2"></i>Ảnh đại diện</h5>
                                    <img src="{{ $profile && $profile->avatar ? asset('storage/' . $profile->avatar) : asset('images/default-avatar.svg') }}"
                                         alt="Avatar" class="rounded-circle mb-3" width="150" height="150" id="avatarPreview">

                                    <form action="{{ route('profile.uploadAvatar') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                                        @csrf
                                        <div class="mb-3">
                                            <input type="file" class="form-control" name="avatar" accept="image/*" id="avatarInput" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="bi bi-upload me-1"></i>Tải ảnh lên
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Medical Information -->
                        <div class="col-md-8 mb-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title mb-4"><i class="bi bi-heart-pulse me-2"></i>Thông tin y tế</h5>

                                    <form action="{{ route('profile.updateMedical') }}" method="POST" id="medicalForm">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Nhóm máu</label>
                                                <select name="nhom_mau" class="form-select">
                                                    <option value="">-- Chọn --</option>
                                                    @foreach(['A', 'B', 'AB', 'O'] as $group)
                                                        <option value="{{ $group }}" {{ $profile->nhom_mau == $group ? 'selected' : '' }}>{{ $group }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Chiều cao (cm)</label>
                                                <input type="number" step="0.1" name="chieu_cao" class="form-control" value="{{ $profile->chieu_cao }}">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Cân nặng (kg)</label>
                                                <input type="number" step="0.1" name="can_nang" class="form-control" value="{{ $profile->can_nang }}">
                                            </div>
                                        </div>

                                        @if($profile->bmi)
                                        <div class="alert alert-info mb-3">
                                            <strong>BMI:</strong> {{ number_format($profile->bmi, 1) }} - {{ $profile->bmi_category }}
                                        </div>
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label">Dị ứng (mỗi dòng 1 loại)</label>
                                            <textarea name="allergies" class="form-control" rows="3">{{ is_array($profile->allergies) ? implode("\n", $profile->allergies) : '' }}</textarea>
                                            <small class="text-muted">Ví dụ: Penicillin, phấn hoa, hải sản...</small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Tiền sử bệnh</label>
                                            <textarea name="tien_su_benh" class="form-control" rows="3">{{ $profile->tien_su_benh }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Thuốc đang dùng</label>
                                            <textarea name="thuoc_dang_dung" class="form-control" rows="3">{{ $profile->thuoc_dang_dung }}</textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Bệnh mãn tính</label>
                                            <textarea name="benh_man_tinh" class="form-control" rows="2">{{ $profile->benh_man_tinh }}</textarea>
                                        </div>

                                        <hr class="my-4">
                                        <h6 class="mb-3"><i class="bi bi-telephone-fill me-2"></i>Liên hệ khẩn cấp</h6>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Họ tên</label>
                                                <input type="text" name="emergency_contact_name" class="form-control" value="{{ $profile->emergency_contact_name }}">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Số điện thoại</label>
                                                <input type="text" name="emergency_contact_phone" class="form-control" value="{{ $profile->emergency_contact_phone }}">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Mối quan hệ</label>
                                                <input type="text" name="emergency_contact_relation" class="form-control" value="{{ $profile->emergency_contact_relation }}" placeholder="Vợ/chồng, con, bạn...">
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-success">
                                            <i class="bi bi-check-circle me-1"></i>Lưu thông tin y tế
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB 3: NOTIFICATIONS -->
                <div class="tab-pane fade" id="notifications" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-4"><i class="bi bi-bell-fill me-2"></i>Cài đặt thông báo</h5>

                            <form action="{{ route('profile.updateNotifications') }}" method="POST">
                                @csrf

                                <h6 class="mb-3">Thông báo qua Email</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="email_reminder" value="1"
                                           id="emailReminder" {{ $preferences->email_reminder ? 'checked' : '' }}>
                                    <label class="form-check-label" for="emailReminder">
                                        Nhắc lịch hẹn sắp tới
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="email_confirmed" value="1"
                                           id="emailConfirmed" {{ $preferences->email_confirmed ? 'checked' : '' }}>
                                    <label class="form-check-label" for="emailConfirmed">
                                        Xác nhận lịch hẹn
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="email_cancelled" value="1"
                                           id="emailCancelled" {{ $preferences->email_cancelled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="emailCancelled">
                                        Hủy lịch hẹn
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="email_test_results" value="1"
                                           id="emailTestResults" {{ $preferences->email_test_results ? 'checked' : '' }}>
                                    <label class="form-check-label" for="emailTestResults">
                                        Kết quả xét nghiệm
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="email_promotions" value="1"
                                           id="emailPromotions" {{ $preferences->email_promotions ? 'checked' : '' }}>
                                    <label class="form-check-label" for="emailPromotions">
                                        Khuyến mãi và tin tức
                                    </label>
                                </div>

                                <hr class="my-4">

                                <h6 class="mb-3">Thông báo qua SMS</h6>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="sms_reminder" value="1"
                                           id="smsReminder" {{ $preferences->sms_reminder ? 'checked' : '' }}>
                                    <label class="form-check-label" for="smsReminder">
                                        Nhắc lịch hẹn sắp tới
                                    </label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="sms_confirmed" value="1"
                                           id="smsConfirmed" {{ $preferences->sms_confirmed ? 'checked' : '' }}>
                                    <label class="form-check-label" for="smsConfirmed">
                                        Xác nhận lịch hẹn
                                    </label>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="sms_cancelled" value="1"
                                           id="smsCancelled" {{ $preferences->sms_cancelled ? 'checked' : '' }}>
                                    <label class="form-check-label" for="smsCancelled">
                                        Hủy lịch hẹn
                                    </label>
                                </div>

                                <hr class="my-4">

                                <h6 class="mb-3">Cài đặt nhắc nhở</h6>
                                <div class="mb-3">
                                    <label class="form-label">Gửi nhắc nhở trước lịch hẹn (giờ)</label>
                                    <select name="reminder_hours_before" class="form-select" style="max-width: 200px;">
                                        <option value="2" {{ $preferences->reminder_hours_before == 2 ? 'selected' : '' }}>2 giờ</option>
                                        <option value="6" {{ $preferences->reminder_hours_before == 6 ? 'selected' : '' }}>6 giờ</option>
                                        <option value="12" {{ $preferences->reminder_hours_before == 12 ? 'selected' : '' }}>12 giờ</option>
                                        <option value="24" {{ $preferences->reminder_hours_before == 24 ? 'selected' : '' }}>24 giờ</option>
                                        <option value="48" {{ $preferences->reminder_hours_before == 48 ? 'selected' : '' }}>48 giờ</option>
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>Lưu cài đặt
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // Avatar preview
        document.getElementById('avatarInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Avatar form AJAX
        document.getElementById('avatarForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang tải...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Cập nhật ảnh đại diện thành công!');
                    location.reload();
                } else {
                    alert('❌ ' + (data.message || 'Có lỗi xảy ra'));
                }
            })
            .catch(error => {
                alert('❌ Lỗi: ' + error.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-upload me-1"></i>Tải ảnh lên';
            });
        });

        // Medical form AJAX
        document.getElementById('medicalForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            // Convert allergies textarea to array
            const allergiesText = formData.get('allergies');
            formData.delete('allergies');
            if (allergiesText && allergiesText.trim()) {
                const allergiesArray = allergiesText.split('\n').filter(line => line.trim());
                allergiesArray.forEach((allergy, index) => {
                    formData.append(`allergies[${index}]`, allergy.trim());
                });
            }

            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang lưu...';

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Cập nhật thông tin y tế thành công!');
                    location.reload();
                } else {
                    alert('❌ ' + (data.message || 'Có lỗi xảy ra'));
                }
            })
            .catch(error => {
                alert('❌ Lỗi: ' + error.message);
            })
            .finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle me-1"></i>Lưu thông tin y tế';
            });
        });
    </script>
    @endpush
</x-app-layout>
