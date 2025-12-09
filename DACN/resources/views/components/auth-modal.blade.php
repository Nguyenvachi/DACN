<!-- AUTH MODAL -->
<div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 24px; overflow: hidden; border: none;">

            {{-- Close Button --}}
            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"
                aria-label="Close" style="z-index: 1000;"></button>

            <div class="row g-0">
                {{-- LEFT SIDE - Branding --}}
                <div class="col-lg-5 d-none d-lg-block position-relative"
                    style="background: linear-gradient(135deg, #10b981, #059669);">
                    <div class="d-flex flex-column justify-content-center align-items-center h-100 p-5 text-white">
                        <div class="text-center mb-4">
                            <div class="vc-logo rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                                style="width: 80px; height: 80px; background: rgba(255,255,255,0.2);">
                                <i class="fas fa-heartbeat" style="font-size: 2.5rem;"></i>
                            </div>
                            <h2 class="fw-bold mb-2">VietCare</h2>
                            <p class="mb-4" style="opacity: 0.95;">Chăm Sóc Sức Khỏe Toàn Diện</p>
                        </div>

                        <div class="w-100">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <i class="fas fa-check-circle" style="font-size: 1.5rem; opacity: 0.9;"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Đội Ngũ Chuyên Nghiệp</h6>
                                    <small style="opacity: 0.9;">Bác sĩ giàu kinh nghiệm</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <i class="fas fa-check-circle" style="font-size: 1.5rem; opacity: 0.9;"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Đặt Lịch Nhanh Chóng</h6>
                                    <small style="opacity: 0.9;">Chỉ trong vài phút</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3">
                                <i class="fas fa-check-circle" style="font-size: 1.5rem; opacity: 0.9;"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Tư Vấn 24/7</h6>
                                    <small style="opacity: 0.9;">Luôn sẵn sàng hỗ trợ</small>
                                </div>
                            </div>
                        </div>

                        <div class="position-absolute bottom-0 start-0 w-100 p-4 text-center"
                            style="background: rgba(0,0,0,0.1);">
                            <small style="opacity: 0.8;">
                                <i class="fas fa-shield-alt me-2"></i>Bảo mật thông tin tuyệt đối
                            </small>
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDE - Forms --}}
                <div class="col-lg-7">
                    <div class="p-5">
                        {{-- Tabs Navigation --}}
                        <ul class="nav nav-pills mb-4 justify-content-center" id="authTabs" role="tablist">
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link active w-100" id="login-tab" data-bs-toggle="pill"
                                    data-bs-target="#login-panel" type="button" role="tab">
                                    <i class="fas fa-sign-in-alt me-2"></i>Đăng Nhập
                                </button>
                            </li>
                            <li class="nav-item flex-fill" role="presentation">
                                <button class="nav-link w-100" id="register-tab" data-bs-toggle="pill"
                                    data-bs-target="#register-panel" type="button" role="tab">
                                    <i class="fas fa-user-plus me-2"></i>Đăng Ký
                                </button>
                            </li>
                        </ul>

                        {{-- Tab Content --}}
                        <div class="tab-content" id="authTabContent">

                            {{-- LOGIN PANEL --}}
                            <div class="tab-pane fade show active" id="login-panel" role="tabpanel">
                                <h4 class="fw-bold mb-4 text-center">Chào Mừng Trở Lại!</h4>

                                <form id="loginForm" method="POST" action="{{ route('login') }}">
                                    @csrf

                                    {{-- Email --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-envelope text-success me-2"></i>Email
                                        </label>
                                        <input type="email" class="form-control form-control-lg" name="email"
                                            required autofocus placeholder="your.email@example.com"
                                            style="border-radius: 12px;">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    {{-- Password --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-lock text-success me-2"></i>Mật Khẩu
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control form-control-lg" name="password"
                                                required placeholder="Nhập mật khẩu"
                                                style="border-radius: 12px; padding-right: 45px;">
                                            <button type="button"
                                                class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                                                onclick="togglePassword('loginForm')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    {{-- Remember & Forgot --}}
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember"
                                                id="remember">
                                            <label class="form-check-label" for="remember">
                                                Ghi nhớ đăng nhập
                                            </label>
                                        </div>
                                        <a href="{{ route('password.request') }}" class="text-decoration-none small"
                                            style="color: #10b981;">
                                            Quên mật khẩu?
                                        </a>
                                    </div>

                                    {{-- Error Alert --}}
                                    <div class="alert alert-danger d-none" id="loginError" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <span class="error-message"></span>
                                    </div>

                                    {{-- Submit Button --}}
                                    <button type="submit" class="btn btn-lg w-100 mb-3 vc-btn-primary">
                                        <i class="fas fa-sign-in-alt me-2"></i>Đăng Nhập
                                    </button>

                                    {{-- Social Login (Optional) --}}
                                    <div class="text-center mb-3">
                                        <small class="text-muted">Hoặc đăng nhập bằng</small>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-secondary flex-fill" disabled>
                                            <i class="fab fa-google me-2"></i>Google
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary flex-fill" disabled>
                                            <i class="fab fa-facebook me-2"></i>Facebook
                                        </button>
                                    </div>
                                </form>
                            </div>

                            {{-- REGISTER PANEL --}}
                            <div class="tab-pane fade" id="register-panel" role="tabpanel">
                                <h4 class="fw-bold mb-4 text-center">Tạo Tài Khoản Mới</h4>

                                <form id="registerForm" method="POST" action="{{ route('register') }}">
                                    @csrf

                                    {{-- Họ Tên --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-user text-success me-2"></i>Họ và Tên <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control form-control-lg" name="name"
                                            required placeholder="Nguyễn Văn A" style="border-radius: 12px;">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    {{-- Email --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-envelope text-success me-2"></i>Email <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control form-control-lg" name="email"
                                            required placeholder="your.email@example.com"
                                            style="border-radius: 12px;">
                                        <small class="text-muted">Chúng tôi sẽ gửi xác nhận qua email này</small>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    {{-- Password --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-lock text-success me-2"></i>Mật Khẩu <span
                                                class="text-danger">*</span>
                                        </label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control form-control-lg"
                                                name="password" required minlength="8"
                                                placeholder="Tối thiểu 8 ký tự"
                                                style="border-radius: 12px; padding-right: 45px;">
                                            <button type="button"
                                                class="btn btn-link position-absolute end-0 top-50 translate-middle-y"
                                                onclick="togglePassword('registerForm')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted">Tối thiểu 8 ký tự, nên có chữ hoa, số và ký tự đặc
                                            biệt</small>
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    {{-- Confirm Password --}}
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-lock text-success me-2"></i>Xác Nhận Mật Khẩu <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="password" class="form-control form-control-lg"
                                            name="password_confirmation" required placeholder="Nhập lại mật khẩu"
                                            style="border-radius: 12px;">
                                        <div class="invalid-feedback"></div>
                                    </div>

                                    {{-- Terms --}}
                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" name="terms"
                                            id="terms" required>
                                        <label class="form-check-label small" for="terms">
                                            Tôi đồng ý với <a href="#" class="text-decoration-none"
                                                style="color: #10b981;">Điều khoản sử dụng</a> và
                                            <a href="#" class="text-decoration-none"
                                                style="color: #10b981;">Chính sách bảo mật</a>
                                        </label>
                                    </div>

                                    {{-- Error Alert --}}
                                    <div class="alert alert-danger d-none" id="registerError" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <span class="error-message"></span>
                                    </div>

                                    {{-- Submit Button --}}
                                    <button type="submit" class="btn btn-lg w-100 vc-btn-primary">
                                        <i class="fas fa-user-plus me-2"></i>Đăng Ký Ngay
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Auth Modal Styles --}}
<style>
    #authModal .nav-pills .nav-link {
        border-radius: 12px;
        font-weight: 600;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
        color: #6b7280;
    }

    #authModal .nav-pills .nav-link.active {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    #authModal .form-control:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
    }

    #authModal .btn-link {
        color: #6b7280;
        text-decoration: none;
        padding: 0.5rem;
    }

    #authModal .btn-link:hover {
        color: #10b981;
    }
</style>

{{-- Auth Modal JavaScript --}}
<script>
    // Toggle password visibility
    function togglePassword(formId) {
        const form = document.getElementById(formId);
        const passwordInput = form.querySelector('input[name="password"]');
        const icon = event.currentTarget.querySelector('i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Handle Login Form Submission
    document.getElementById('loginForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const errorAlert = document.getElementById('loginError');
        const formData = new FormData(form);

        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';

        // Hide previous errors
        errorAlert.classList.add('d-none');
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                // Success - reload page
                window.location.reload();
            } else {
                // Show errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const feedback = input.nextElementSibling;
                            if (feedback && feedback.classList.contains('invalid-feedback')) {
                                feedback.textContent = data.errors[key][0];
                            }
                        }
                    });
                }
                if (data.message) {
                    errorAlert.querySelector('.error-message').textContent = data.message;
                    errorAlert.classList.remove('d-none');
                }
            }
        } catch (error) {
            errorAlert.querySelector('.error-message').textContent = 'Có lỗi xảy ra. Vui lòng thử lại.';
            errorAlert.classList.remove('d-none');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-sign-in-alt me-2"></i>Đăng Nhập';
        }
    });

    // Handle Register Form Submission
    document.getElementById('registerForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const errorAlert = document.getElementById('registerError');
        const formData = new FormData(form);

        // Disable submit button
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';

        // Hide previous errors
        errorAlert.classList.add('d-none');
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                // Success - reload page
                window.location.reload();
            } else {
                // Show errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const feedback = input.parentElement.querySelector(
                                '.invalid-feedback') ||
                                input.nextElementSibling;
                            if (feedback && feedback.classList.contains('invalid-feedback')) {
                                feedback.textContent = data.errors[key][0];
                            }
                        }
                    });
                }
                if (data.message) {
                    errorAlert.querySelector('.error-message').textContent = data.message;
                    errorAlert.classList.remove('d-none');
                }
            }
        } catch (error) {
            errorAlert.querySelector('.error-message').textContent = 'Có lỗi xảy ra. Vui lòng thử lại.';
            errorAlert.classList.remove('d-none');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-user-plus me-2"></i>Đăng Ký Ngay';
        }
    });

    // Switch to register tab from URL parameter
    if (window.location.hash === '#register') {
        const registerTab = document.getElementById('register-tab');
        if (registerTab) {
            const tab = new bootstrap.Tab(registerTab);
            tab.show();
        }
    }
</script>
