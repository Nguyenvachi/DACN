<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">{{ __("You're logged in!") }}</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                        <!-- Card Dịch vụ -->
                        <a href="{{ route('public.dichvu.index') }}"
                            class="block p-6 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition">
                            <h4 class="text-xl font-bold text-blue-700 dark:text-blue-300 mb-2">
                                📋 Dịch vụ
                            </h4>
                            <p class="text-gray-600 dark:text-gray-400">
                                Xem danh sách dịch vụ khám bệnh →
                            </p>
                        </a>

                        <!-- Card Bác sĩ -->
                        <a href="{{ route('public.bacsi.index') }}"
                            class="block p-6 bg-green-50 dark:bg-green-900 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition">
                            <h4 class="text-xl font-bold text-green-700 dark:text-green-300 mb-2">
                                👨‍⚕️ Bác sĩ
                            </h4>
                            <p class="text-gray-600 dark:text-gray-400">
                                Xem danh sách bác sĩ và đặt lịch →
                            </p>
                        </a>
                    </div>

                    <!-- Link lịch hẹn của tôi -->
                    <div class="mt-6">
                        <a href="{{ route('lichhen.my') }}"
                            class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                            📅 Xem lịch hẹn của tôi
                        </a>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('public.blog.index') }}"
                            class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                            📰 Đọc tin tức y khoa
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>