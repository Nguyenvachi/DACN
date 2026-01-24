@php
    $isPatient = $isPatient ?? false;
    $user = $user ?? auth()->user();
@endphp

{{-- PHẦN GIAO DIỆN DÀNH RIÊNG CHO BỆNH NHÂN (BOOTSTRAP 5 SOFT UI) --}}
@if ($isPatient)
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom-0">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="bi bi-person-vcard me-2"></i>Thông tin tài khoản
            </h5>
            <p class="text-muted small mb-0 mt-1">Cập nhật thông tin cá nhân và địa chỉ email của bạn.</p>
        </div>

        <div class="card-body p-4 pt-0">
            {{-- Form gửi xác minh email (Ẩn) --}}
            <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                @csrf
            </form>

            <form method="post" action="{{ route('profile.update') }}" class="mt-2">
                @csrf
                @method('patch')

                {{-- HỌ VÀ TÊN --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary small text-uppercase">Họ và Tên</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-primary">
                            <i class="bi bi-person-fill"></i>
                        </span>
                        <input type="text" name="name"
                            class="form-control bg-light border-start-0 ps-0 py-2 fw-medium @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- EMAIL --}}
                <div class="mb-4">
                    <label class="form-label fw-bold text-secondary small text-uppercase">Email Đăng nhập</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-primary">
                            <i class="bi bi-envelope-fill"></i>
                        </span>
                        <input type="email" name="email"
                            class="form-control bg-light border-start-0 ps-0 py-2 fw-medium @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}" required autocomplete="username">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Logic Xác minh Email --}}
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                        <div
                            class="mt-3 p-3 bg-warning bg-opacity-10 rounded-3 border border-warning border-opacity-25">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                                <span class="text-dark fw-medium small">Email của bạn chưa được xác minh.</span>
                            </div>
                            <button form="send-verification"
                                class="btn btn-sm btn-outline-warning text-dark fw-bold w-100">
                                <i class="bi bi-send me-1"></i> Gửi lại email xác minh
                            </button>

                            @if (session('status') === 'verification-link-sent')
                                <div class="mt-2 text-success small fw-bold">
                                    <i class="bi bi-check-circle-fill me-1"></i>
                                    Liên kết xác minh mới đã được gửi đến email của bạn.
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="d-flex align-items-center gap-3 mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm hover-up">
                        <i class="bi bi-save me-2"></i>Lưu thay đổi
                    </button>

                    @if (session('status') === 'profile-updated')
                        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                            class="d-flex align-items-center text-success fw-bold small">
                            <i class="bi bi-check-circle-fill me-1"></i> Đã lưu.
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- PHẦN GIAO DIỆN MẶC ĐỊNH (TAILWIND - GIỮ NGUYÊN CHO CÁC ROLE KHÁC) --}}
@else
    <section class="space-y-4">
        <header>
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Thông tin tài khoản
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Cập nhật thông tin cá nhân và địa chỉ email của bạn.
            </p>
        </header>

        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <form method="post" action="{{ route('profile.update') }}" class="mt-4 space-y-6">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="name" :value="__('Họ và Tên')"
                    class="font-semibold text-gray-700 dark:text-gray-300" />
                <x-text-input id="name" name="name" type="text"
                    class="mt-1 block w-full rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')"
                    class="font-semibold text-gray-700 dark:text-gray-300" />
                <x-text-input id="email" name="email" type="email"
                    class="mt-1 block w-full rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="mt-2 space-y-1">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Email của bạn chưa được xác minh.
                            <button form="send-verification"
                                class="underline text-sm text-blue-600 hover:text-blue-800 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Gửi lại email xác minh
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                Liên kết xác minh mới đã được gửi đến email của bạn.
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button
                    class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 transition rounded-lg px-4 py-2">{{ __('Lưu thay đổi') }}</x-primary-button>
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600 dark:text-gray-400">Đã lưu.</p>
                @endif
            </div>
        </form>
    </section>
@endif
