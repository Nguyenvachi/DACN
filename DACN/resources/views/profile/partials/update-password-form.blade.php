<section class="space-y-4">

    {{-- HEADER --}}
    <header>
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 2c-2.667 0-8 1.333-8 4v2h16v-2c0-2.667-5.333-4-8-4z" />
            </svg>
            Đổi mật khẩu
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Hãy sử dụng mật khẩu mạnh để tăng mức độ bảo mật cho tài khoản của bạn.
        </p>
    </header>

    {{-- FORM --}}
    <form method="post" action="{{ route('password.update') }}" class="mt-4 space-y-6">
        @csrf
        @method('put')

        {{-- Hidden username for accessibility / password managers --}}
        <input type="text" name="username" value="{{ old('email', auth()->user()->email ?? '') }}" autocomplete="username" class="d-none" aria-hidden="true" tabindex="-1">

        {{-- Mật khẩu hiện tại --}}
        <div>
            <x-input-label for="current_password" :value="__('Mật khẩu hiện tại')" class="font-semibold" />
            <x-text-input id="current_password" name="current_password" type="password"
                class="mt-1 block w-full rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500"
                autocomplete="current-password" />

            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        {{-- Mật khẩu mới --}}
        <div>
            <x-input-label for="password" :value="__('Mật khẩu mới')" class="font-semibold" />
            <x-text-input id="password" name="password" type="password"
                class="mt-1 block w-full rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500"
                autocomplete="new-password" />

            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        {{-- Xác nhận mật khẩu --}}
        <div>
            <x-input-label for="password_confirmation" :value="__('Xác nhận mật khẩu')" class="font-semibold" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500"
                autocomplete="new-password" />

            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        {{-- BUTTON --}}
        <div class="flex items-center gap-4">
            <x-primary-button
                class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 transition rounded-lg px-4 py-2">
                {{ __('Lưu thay đổi') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">
                    Đã lưu.
                </p>
            @endif
        </div>
    </form>

</section>
