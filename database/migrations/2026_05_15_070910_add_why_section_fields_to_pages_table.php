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
        Schema::table('pages', function (Blueprint $table) {
            $table->json('why_eyebrow')->nullable()->after('services_lede');
            $table->json('why_heading')->nullable()->after('why_eyebrow');
            $table->string('why_image')->nullable()->after('why_heading');
            $table->json('why_caption')->nullable()->after('why_image');
            $table->string('why_metric1_value', 20)->nullable()->after('why_caption');
            $table->json('why_metric1_label')->nullable()->after('why_metric1_value');
            $table->string('why_metric2_value', 20)->nullable()->after('why_metric1_label');
            $table->json('why_metric2_label')->nullable()->after('why_metric2_value');
            $table->json('why_item1_title')->nullable()->after('why_metric2_label');
            $table->json('why_item1_desc')->nullable()->after('why_item1_title');
            $table->json('why_item2_title')->nullable()->after('why_item1_desc');
            $table->json('why_item2_desc')->nullable()->after('why_item2_title');
            $table->json('why_item3_title')->nullable()->after('why_item2_desc');
            $table->json('why_item3_desc')->nullable()->after('why_item3_title');
            $table->json('why_item4_title')->nullable()->after('why_item3_desc');
            $table->json('why_item4_desc')->nullable()->after('why_item4_title');
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'why_eyebrow', 'why_heading', 'why_image', 'why_caption',
                'why_metric1_value', 'why_metric1_label',
                'why_metric2_value', 'why_metric2_label',
                'why_item1_title', 'why_item1_desc',
                'why_item2_title', 'why_item2_desc',
                'why_item3_title', 'why_item3_desc',
                'why_item4_title', 'why_item4_desc',
            ]);
        });
    }
};
