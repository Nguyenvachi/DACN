<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class SynchronizeUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allowed = ['admin', 'doctor', 'staff', 'patient'];

        $mapping = [
            'super-admin' => 'admin',
            'manager' => 'admin',
            'accountant' => 'admin',
            'pharmacist' => 'staff',
            'pharmacy' => 'staff',
            'nurse' => 'staff',
            'reception' => 'staff',
        ];

        User::chunk(100, function ($users) use ($allowed, $mapping) {
            foreach ($users as $user) {
                $orig = Str::lower(trim($user->role ?? ''));

                if (empty($orig)) {
                    $this->command->info("Skipping user {$user->email}: empty role field");
                    continue;
                }

                // Determine normalized role
                if (in_array($orig, $allowed, true)) {
                    $normalized = $orig;
                } elseif (isset($mapping[$orig])) {
                    $normalized = $mapping[$orig];
                } else {
                    // If user already has allowed role via Spatie, prefer that
                    $existing = $user->getRoleNames()->map(fn($r) => Str::lower($r))->first(function ($r) use ($allowed) {
                        return in_array($r, $allowed, true);
                    });

                    if ($existing) {
                        $normalized = $existing;
                    } else {
                        // fallback to patient to avoid accidental admin grant
                        $normalized = 'patient';
                    }
                }

                try {
                    // Update DB column
                    $user->role = $normalized;
                    $user->save();

                    // Sync Spatie role to ensure only normalized role present
                    $user->syncRoles([$normalized]);

                    $this->command->info("Normalized role for {$user->email}: {$orig} => {$normalized}");
                } catch (\Exception $e) {
                    $this->command->error("Failed to normalize role for {$user->email}: {$e->getMessage()}");
                }
            }
        });
    }
}
