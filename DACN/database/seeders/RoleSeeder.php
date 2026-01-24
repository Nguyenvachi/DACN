<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Tạo các vai trò mặc định và gán permissions.
     */
    public function run()
    {
        // Reset cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Super Admin - Full quyền
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());
        $this->command->info('✅ Role: super-admin - Full quyền');

        // 2. Admin - Quản trị hệ thống (không có manage roles/permissions)
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'view-users', 'create-users', 'edit-users', 'lock-users', 'unlock-users',
            'view-doctors', 'create-doctors', 'edit-doctors', 'manage-schedules',
            'view-appointments', 'create-appointments', 'edit-appointments', 'cancel-appointments', 'confirm-appointments',
            'view-medical-records', 'create-medical-records', 'edit-medical-records', 'delete-medical-records',
            'view-services', 'create-services', 'edit-services',
            'view-staff', 'create-staff', 'edit-staff', 'view-staff-shifts', 'assign-staff-shifts',
            'view-invoices', 'create-invoices', 'edit-invoices', 'process-payments', 'view-payment-logs',
            'view-reports', 'view-revenue-reports', 'view-appointment-reports', 'export-data',
            'view-dashboard',
            'send-notifications',  // Thêm mới
        ]);
        $this->command->info('✅ Role: admin - Quản trị hệ thống');

        // 3. Manager - Quản lý phòng khám (không tạo user)
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->syncPermissions([
            'view-users',
            'view-doctors', 'edit-doctors', 'manage-schedules',
            'view-appointments', 'edit-appointments', 'cancel-appointments', 'confirm-appointments',
            'view-services',
            'view-staff', 'view-staff-shifts',
            'view-invoices', 'process-payments',
            'view-reports', 'view-revenue-reports', 'view-appointment-reports',
            'view-dashboard',
        ]);
        $this->command->info('✅ Role: manager - Quản lý phòng khám');

        // 4. Doctor - Bác sĩ (quản lý bệnh án + lịch hẹn của mình)
        $doctor = Role::firstOrCreate(['name' => 'doctor', 'guard_name' => 'web']);
        $doctor->syncPermissions([
            'view-appointments', 'edit-appointments', 'confirm-appointments',
            'view-medical-records', 'create-medical-records', 'edit-medical-records',
            'view-services',
            'view-medicines',
        ]);
        $this->command->info('✅ Role: doctor - Bác sĩ');

        // 5. Staff - Nhân viên y tế
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staff->syncPermissions([
            'view-appointments', 'create-appointments', 'edit-appointments',
            'view-medical-records',
            'view-services',
            'view-invoices',
            'view-dashboard',
        ]);
        $this->command->info('✅ Role: staff - Nhân viên');

        // 6. Patient - Bệnh nhân (chỉ xem thông tin cá nhân)
        $patient = Role::firstOrCreate(['name' => 'patient', 'guard_name' => 'web']);
        $patient->syncPermissions([
            'view-appointments',
            'view-services',
        ]);
        $this->command->info('✅ Role: patient - Bệnh nhân');

        // 7. Accountant - Kế toán
        $accountant = Role::firstOrCreate(['name' => 'accountant', 'guard_name' => 'web']);
        $accountant->syncPermissions([
            'view-invoices', 'create-invoices', 'edit-invoices', 'process-payments', 'refund-payments', 'view-payment-logs',
            'view-reports', 'view-revenue-reports', 'export-data',
        ]);
        $this->command->info('✅ Role: accountant - Kế toán');

        // 8. Pharmacist - Dược sĩ
        $pharmacist = Role::firstOrCreate(['name' => 'pharmacist', 'guard_name' => 'web']);
        $pharmacist->syncPermissions([
            'view-medicines', 'create-medicines', 'edit-medicines',
            'manage-inventory', 'view-inventory-reports',
        ]);
        $this->command->info('✅ Role: pharmacist - Dược sĩ');
    }
}
