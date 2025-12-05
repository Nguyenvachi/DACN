<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-800 leading-tight flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Dashboard Sức khỏe
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Thống kê tổng quan -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Total Appointments -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Tổng lịch hẹn</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $statistics['total_appointments'] }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 rounded-full">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Completed -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Đã hoàn thành</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $statistics['completed_appointments'] }}</p>
                        </div>
                        <div class="p-3 bg-green-100 rounded-full">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Medical Records -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Bệnh án</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $statistics['total_medical_records'] }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-full">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Chờ xác nhận</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $statistics['pending_appointments'] }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Cột trái: Lịch hẹn & Bệnh án -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Lịch hẹn sắp tới -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Lịch hẹn sắp tới
                            </h3>
                            <a href="{{ route('lichhen.my') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Xem tất cả →
                            </a>
                        </div>

                        @if($upcomingAppointments->isEmpty())
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p>Bạn chưa có lịch hẹn nào</p>
                                <a href="{{ route('lichhen.create') }}" class="mt-3 inline-block text-blue-600 hover:text-blue-800 font-medium">
                                    Đặt lịch ngay
                                </a>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($upcomingAppointments as $appointment)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center mb-2">
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                                        {{ $appointment->trang_thai === 'confirmed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ $appointment->trang_thai === 'confirmed' ? 'Đã xác nhận' : 'Chờ xác nhận' }}
                                                    </span>
                                                </div>
                                                <p class="font-semibold text-gray-800">{{ $appointment->dichVu->ten_dich_vu ?? 'Khám bệnh' }}</p>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    <span class="font-medium">Bác sĩ:</span> {{ $appointment->bacSi->user->name ?? 'N/A' }}
                                                </p>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    <span class="font-medium">Thời gian:</span>
                                                    {{ \Carbon\Carbon::parse($appointment->ngay_hen)->format('d/m/Y') }} - {{ substr($appointment->thoi_gian_hen, 0, 5) }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Bệnh án gần đây -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Bệnh án gần đây
                            </h3>
                        </div>

                        @if($recentMedicalRecords->isEmpty())
                            <div class="text-center py-8 text-gray-500">
                                <svg class="w-16 h-16 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p>Chưa có bệnh án nào</p>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach($recentMedicalRecords as $record)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-800">{{ $record->chan_doan }}</p>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    <span class="font-medium">Bác sĩ:</span> {{ $record->bacSi->user->name ?? 'N/A' }}
                                                </p>
                                                <p class="text-sm text-gray-600">
                                                    <span class="font-medium">Ngày khám:</span>
                                                    {{ \Carbon\Carbon::parse($record->ngay_kham)->format('d/m/Y') }}
                                                </p>
                                            </div>
                                            @if($record->id)
                                                <a href="{{ route('patient.benhan.exportPdf', $record->id) }}"
                                                   class="ml-3 text-blue-600 hover:text-blue-800"
                                                   title="Tải PDF">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Cột phải: Health Stats & BMI Chart -->
                <div class="space-y-6">

                    <!-- Health Stats -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            Chỉ số sức khỏe
                        </h3>

                        @if($profile)
                            <div class="space-y-4">
                                <!-- BMI -->
                                @if($healthStats['bmi'])
                                    <div class="p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg">
                                        <p class="text-sm text-gray-600 mb-1">Chỉ số BMI</p>
                                        <p class="text-3xl font-bold text-blue-700">{{ number_format($healthStats['bmi'], 1) }}</p>
                                        <p class="text-sm text-gray-700 mt-1">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                @if($healthStats['bmi_category'] === 'Bình thường') bg-green-200 text-green-800
                                                @elseif($healthStats['bmi_category'] === 'Thiếu cân') bg-yellow-200 text-yellow-800
                                                @else bg-orange-200 text-orange-800
                                                @endif">
                                                {{ $healthStats['bmi_category'] }}
                                            </span>
                                        </p>
                                    </div>
                                @endif

                                <!-- Other stats -->
                                <div class="grid grid-cols-2 gap-3">
                                    @if($healthStats['blood_type'])
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-gray-600">Nhóm máu</p>
                                            <p class="text-lg font-bold text-gray-800">{{ $healthStats['blood_type'] }}</p>
                                        </div>
                                    @endif

                                    @if($healthStats['height'])
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-gray-600">Chiều cao</p>
                                            <p class="text-lg font-bold text-gray-800">{{ $healthStats['height'] }} cm</p>
                                        </div>
                                    @endif

                                    @if($healthStats['weight'])
                                        <div class="p-3 bg-gray-50 rounded-lg">
                                            <p class="text-xs text-gray-600">Cân nặng</p>
                                            <p class="text-lg font-bold text-gray-800">{{ $healthStats['weight'] }} kg</p>
                                        </div>
                                    @endif

                                    <div class="p-3 bg-gray-50 rounded-lg">
                                        <p class="text-xs text-gray-600">Dị ứng</p>
                                        <p class="text-lg font-bold text-gray-800">{{ $healthStats['allergies_count'] }}</p>
                                    </div>
                                </div>

                                <a href="{{ route('profile.edit') }}" class="block w-full text-center py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                                    Cập nhật hồ sơ y tế
                                </a>
                            </div>
                        @else
                            <div class="text-center py-8 text-gray-500">
                                <p class="mb-3">Chưa cập nhật thông tin y tế</p>
                                <a href="{{ route('profile.edit') }}" class="inline-block px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                                    Cập nhật ngay
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- BMI Chart -->
                    @if($healthStats['bmi'])
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                Xu hướng BMI (6 tháng)
                            </h3>
                            <canvas id="bmiChart" height="200"></canvas>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </div>

    @if($healthStats['bmi'])
        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script>
            const ctx = document.getElementById('bmiChart').getContext('2d');
            const bmiChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($bmiHistory['labels']),
                    datasets: [{
                        label: 'BMI',
                        data: @json($bmiHistory['data']),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 18,
                            max: 30,
                            ticks: {
                                stepSize: 2
                            }
                        }
                    }
                }
            });
        </script>
        @endpush
    @endif

</x-app-layout>
