<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('company')->nullable()->after('email');
            $table->string('phone')->nullable()->after('company');
            $table->timestamp('approved_at')->nullable()->after('password');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');
            $table->timestamp('gdpr_consent_at')->nullable()->after('approved_by');
            $table->string('locale', 2)->default('fr')->after('gdpr_consent_at');
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['company', 'phone', 'approved_at', 'approved_by', 'gdpr_consent_at', 'locale']);
        });
    }
};
