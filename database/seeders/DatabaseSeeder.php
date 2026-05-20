<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            PagesSeeder::class,
            ProductCategorySeeder::class,
            BlogPostSeeder::class,
            ServicesSeeder::class,
            SiteSettingsSeeder::class,
            LegalPagesSeeder::class,
            BlogPageSeeder::class,
            MethodStepsSeeder::class,
            ContentSeeder::class,
        ]);
    }
}
