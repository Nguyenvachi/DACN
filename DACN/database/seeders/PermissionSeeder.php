<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Tạo các quyền mặc định cho hệ thống.
     */
    public function run()
    {
        // Reset cache permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Danh sách permissions
        $permissions = [
            // Quản lý Users
            'view-users',
            'create-users',
            'edit-users',
            'delete-users',
            'lock-users',
            'unlock-users',
            'assign-roles',

            // Quản lý Roles & Permissions
            'view-roles',
            'create-roles',
            'edit-roles',
            'delete-roles',
            'view-permissions',
            'create-permissions',
            'delete-permissions',

            // Quản lý Bác sĩ
            'view-doctors',
            'create-doctors',
            'edit-doctors',
            'delete-doctors',
            'manage-schedules',

            // Quản lý Lịch hẹn
            'view-appointments',
            'create-appointments',
            'edit-appointments',
            'cancel-appointments',
            'confirm-appointments',

            // Quản lý Bệnh án
            'view-medical-records',
            'create-medical-records',
            'edit-medical-records',
            'delete-medical-records',

            // Quản lý Dịch vụ
            'view-services',
            'create-services',
            'edit-services',
            'delete-services',

            // Quản lý Thuốc & Kho
            'view-medicines',
            'create-medicines',
            'edit-medicines',
            'delete-medicines',
            'manage-inventory',
            'view-inventory-reports',

            // Quản lý Nhân viên
            'view-staff',
            'create-staff',
            'edit-staff',
            'delete-staff',
            'view-staff-shifts',
            'assign-staff-shifts',

            // Quản lý Hóa đơn & Thanh toán
            'view-invoices',
            'create-invoices',
            'edit-invoices',
            'delete-invoices',
            'process-payments',
            'refund-payments',
            'view-payment-logs',

            // Báo cáo & Thống kê
            'view-reports',
            'view-revenue-reports',
            'view-appointment-reports',
            'export-data',
            'view-dashboard',
            'send-notifications',  // Thêm mới
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $this->command->info('✅ Đã tạo ' . count($permissions) . ' permissions');
    }
}
