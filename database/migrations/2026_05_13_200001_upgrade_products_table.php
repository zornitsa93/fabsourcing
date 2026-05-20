<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Rename existing columns to align with new spec
            $table->renameColumn('title', 'name');
            $table->renameColumn('description', 'short_description');
            $table->renameColumn('image', 'main_image');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->unique()->after('product_category_id');
            $table->json('full_description')->nullable()->after('short_description');
            $table->json('features')->nullable()->after('full_description');
            $table->json('materials')->nullable()->after('features');
            $table->json('specifications')->nullable()->after('materials');
            $table->json('gallery_images')->nullable()->after('main_image');
            $table->boolean('published')->default(false)->after('sort_order');
            $table->json('meta_title')->nullable()->after('published');
            $table->json('meta_description')->nullable()->after('meta_title');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('name', 'title');
            $table->renameColumn('short_description', 'description');
            $table->renameColumn('main_image', 'image');
            $table->dropColumn([
                'slug', 'full_description', 'features', 'materials',
                'specifications', 'gallery_images', 'published', 'meta_title', 'meta_description',
            ]);
        });
    }
};
