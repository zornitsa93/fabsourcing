<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::firstOrCreate(
            ['email' => 'admin@fab-sourcing.fr'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@fab-sourcing.fr',
                'password' => Hash::make('changeme'),
                'isMaster' => true,
            ]
        );
    }
}
