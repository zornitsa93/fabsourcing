<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'phone',   'value' => '+33782085117',               'type' => 'text',  'translatable' => false],
            ['key' => 'phone_display', 'value' => '+33 (0)7 82 08 51 17', 'type' => 'text',  'translatable' => false],
            ['key' => 'email',   'value' => 'tsudol.fabtec@yahoo.com',    'type' => 'text',  'translatable' => false],
            ['key' => 'address', 'value' => '1, route Neuve — 24150 St-Capraise-de-Lalinde, France', 'type' => 'text', 'translatable' => false],
        ];

        foreach ($settings as $data) {
            SiteSetting::updateOrCreate(['key' => $data['key']], $data);
        }
    }
}
