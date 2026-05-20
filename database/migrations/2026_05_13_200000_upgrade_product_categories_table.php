<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->string('slug')->unique()->after('id');
            $table->json('description')->nullable()->after('name');
            $table->string('icon', 100)->nullable()->after('description');
            $table->boolean('published')->default(false)->after('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropColumn(['slug', 'description', 'icon', 'published']);
        });
    }
};
