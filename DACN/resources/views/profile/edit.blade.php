<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-800 leading-tight flex items-center">
            <!-- Icon Profile -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 01112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6
                       2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ __('Quản lý Hồ sơ cá nhân') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- GRID 2 CỘT -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- ================================
                     CỘT 1 — THÔNG TIN CÁ NHÂN
                ================================= -->
                <div class="lg:col-span-2">
                    <div
                        class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow-lg sm:rounded-xl border border-gray-100 dark:border-gray-700">

                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Thông tin cơ bản
                        </h3>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            @include('profile.partials.update-profile-information-form')
                        </div>

                    </div>
                </div>

                <!-- ================================
                     CỘT 2 — BẢO MẬT & NGUY HIỂM
                ================================= -->
                <div class="lg:col-span-1 space-y-6">

                    <!-- Đổi mật khẩu -->
                    <div
                        class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow-md sm:rounded-xl border border-yellow-300/40">

                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.1 0 2-.9 2-2V7a4 4 0 00-8 0v2c0 1.1.9 2 2 2h4zm-6 4h12a2
                                       2 0 012 2v4a2 2 0 01-2 2H6a2
                                       2 0 01-2-2v-4a2 2 0 012-2z" />
                            </svg>
                            Bảo mật tài khoản
                        </h3>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            @include('profile.partials.update-password-form')
                        </div>

                    </div>

                    <!-- Xóa tài khoản -->
                    <div class="p-6 sm:p-8 bg-white dark:bg-gray-800 shadow-md sm:rounded-xl border border-red-400/40">

                        <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-3 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M5.002
                                       20h13.996c1.54 0 2.502-1.667
                                       1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464
                                       0L3.27 17c-.77 1.333.192 3
                                       1.732 3z" />
                            </svg>
                            Khu vực nguy hiểm
                        </h3>

                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Thao tác này không thể hoàn tác. Hãy cân nhắc trước khi xóa tài khoản.
                        </p>

                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            @include('profile.partials.delete-user-form')
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
