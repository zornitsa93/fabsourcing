<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('number');
            $table->boolean('published')->default(true)->after('slug');
            $table->json('long_description')->nullable()->after('description');
            $table->string('image')->nullable()->after('long_description');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['slug', 'published', 'long_description', 'image']);
        });
    }
};
