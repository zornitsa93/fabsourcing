<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $categories = [
            1 => ['slug' => 'charpente-structures-metalliques-acier',  'slug_en' => 'steel-structure-metalwork'],
            2 => ['slug' => 'escaliers-metalliques-sur-mesure',         'slug_en' => 'custom-metal-stairs'],
            3 => ['slug' => 'garde-corps-rampes-inox-acier',            'slug_en' => 'stainless-steel-railings-handrails'],
            4 => ['slug' => 'fenetres-portes-menuiseries-metalliques',  'slug_en' => 'metal-windows-doors-joinery'],
            5 => ['slug' => 'bardage-facade-metallique-industriel',     'slug_en' => 'industrial-metal-cladding-facade'],
            6 => ['slug' => 'verriere-atelier-cloison-vitree',          'slug_en' => 'workshop-glass-roof-partition'],
            7 => ['slug' => 'portail-cloture-acier-sur-mesure',         'slug_en' => 'custom-steel-gate-fence'],
            8 => ['slug' => 'terrasse-balcon-garde-corps-exterieur',    'slug_en' => 'terrace-balcony-outdoor-railing'],
            9 => ['slug' => 'rack-industriel-shelter-stockage',         'slug_en' => 'industrial-rack-storage-shelter'],
        ];

        foreach ($categories as $id => $slugs) {
            DB::table('product_categories')
                ->where('id', $id)
                ->update($slugs);
        }
    }

    public function down(): void
    {
        $categories = [
            1 => ['slug' => 'structures-metalliques',  'slug_en' => 'steel-structures'],
            2 => ['slug' => 'escaliers-metalliques',   'slug_en' => 'metal-stairs'],
            3 => ['slug' => 'garde-corps-rampes',      'slug_en' => 'railings-handrails'],
            4 => ['slug' => 'menuiseries-metalliques', 'slug_en' => 'metal-joinery'],
            5 => ['slug' => 'bardages-facades',        'slug_en' => 'cladding-facades'],
            6 => ['slug' => 'verrieres-cloisons',      'slug_en' => 'glass-roofs-partitions'],
            7 => ['slug' => 'portails-clotures',       'slug_en' => 'gates-fences'],
            8 => ['slug' => 'terrasses-balcons',       'slug_en' => 'terraces-balconies'],
            9 => ['slug' => 'racks-shelters',          'slug_en' => 'industrial-racks-shelters'],
        ];

        foreach ($categories as $id => $slugs) {
            DB::table('product_categories')
                ->where('id', $id)
                ->update($slugs);
        }
    }
};
