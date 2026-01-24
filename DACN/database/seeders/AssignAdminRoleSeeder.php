<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssignAdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::where('email', 'Admin@gmail.com')->first();
        if ($user) {
            $user->assignRole('admin');
            $this->command->info('Assigned admin role to ' . $user->email);
        } else {
            $this->command->error('User Admin@gmail.com not found');
        }
    }
}
