<section class="space-y-6">

    {{-- HEADER --}}
    <header>
        <h2 class="text-xl font-semibold text-red-600 dark:text-red-400 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01M5.07 19h13.86c1.14 0 1.95-1.2 1.5-2.27L13.5 4.73a1.71 1.71 0 00-3 0L3.57 16.73C3.12 17.8 3.93 19 5.07 19z" />
            </svg>
            Khu vực nguy hiểm
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
            Khi xóa tài khoản, <span class="font-semibold text-red-600">mọi dữ liệu sẽ bị xóa vĩnh viễn</span>.
            Vui lòng sao lưu các thông tin quan trọng trước khi thực hiện thao tác này.
        </p>
    </header>

    {{-- DELETE BUTTON --}}
    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="!bg-red-600 hover:!bg-red-700 !text-white px-4 py-2 rounded-lg shadow-md">
        <i class="fas fa-trash-alt mr-1"></i> Xóa tài khoản
    </x-danger-button>

    {{-- MODAL CONFIRM --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-5">
            @csrf
            @method('delete')

            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M5.07 19h13.86c1.14 0 1.95-1.2 1.5-2.27L13.5 4.73a1.71 1.71 0 00-3 0L3.57 16.73C3.12 17.8 3.93 19 5.07 19z" />
                </svg>
                Bạn có chắc chắn muốn xóa tài khoản?
            </h2>

            <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                Hành động này <span class="text-red-600 font-semibold">không thể hoàn tác</span>.
                Vui lòng nhập mật khẩu để xác nhận xóa tài khoản vĩnh viễn.
            </p>

            {{-- PASSWORD INPUT --}}
            <div class="mt-4">
                <x-input-label for="password" value="Mật khẩu" class="font-semibold" />
                <x-text-input id="password" name="password" type="password"
                    class="mt-2 block w-3/4 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500"
                    placeholder="Nhập mật khẩu của bạn..." />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="mt-6 flex justify-end gap-3">

                <x-secondary-button x-on:click="$dispatch('close')" class="px-4 py-2 rounded-lg">
                    Hủy
                </x-secondary-button>

                <x-danger-button class="!bg-red-600 hover:!bg-red-700 !text-white px-4 py-2 rounded-lg shadow-md">
                    Xóa tài khoản
                </x-danger-button>

            </div>
        </form>
    </x-modal>

</section>
