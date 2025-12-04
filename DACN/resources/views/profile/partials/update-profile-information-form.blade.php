<section class="space-y-4">

    {{-- HEADER --}}
    <header>
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Thông tin tài khoản
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Cập nhật thông tin cá nhân và địa chỉ email của bạn.
        </p>
    </header>


    {{-- FORM GỬI XÁC MINH EMAIL --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>


    {{-- FORM CHÍNH --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-4 space-y-6">
        @csrf
        @method('patch')

        {{-- TÊN --}}
        <div>
            <x-input-label for="name" :value="__('Họ và Tên')" class="font-semibold text-gray-700 dark:text-gray-300" />
            <x-text-input id="name" name="name" type="text"
                class="mt-1 block w-full rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                :value="old('name', $user->name)" required autofocus autocomplete="name" />

            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- EMAIL --}}
        <div>
            <x-input-label for="email" :value="__('Email')" class="font-semibold text-gray-700 dark:text-gray-300" />
            <x-text-input id="email" name="email" type="email"
                class="mt-1 block w-full rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                :value="old('email', $user->email)" required autocomplete="username" />

            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            {{-- Xác minh email --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-2 space-y-1">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        Email của bạn chưa được xác minh.

                        <button form="send-verification"
                            class="underline text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                            Gửi lại email xác minh
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="text-sm font-semibold text-green-600 dark:text-green-400">
                            Liên kết xác minh mới đã được gửi đến email của bạn.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- BUTTON LƯU --}}
        <div class="flex items-center gap-4">
            <x-primary-button class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 transition rounded-lg px-4 py-2">
                {{ __('Lưu thay đổi') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">
                    Đã lưu.
                </p>
            @endif
        </div>
    </form>

</section>
