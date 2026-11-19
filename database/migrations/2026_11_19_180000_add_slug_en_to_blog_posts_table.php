<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** French slug => English slug (from the SEO dossier, FR/EN URL table). */
    private const SLUGS = [
        'norme-en-1090-donneur-ordre-structure-metallique'      => 'en-1090-what-to-require-from-steel-subcontractor',
        'cout-cache-sous-traitance-prix-unitaire-tco'           => 'hidden-cost-subcontracting-unit-price-tco',
        'externalisation-partielle-totale-strategie-production' => 'partial-or-full-outsourcing-metal-production',
        'penurie-soudeurs-france-externalisation-structurelle'  => 'welder-shortage-outsourcing-structural-solution',
        'bulgarie-alternative-asie-sous-traitance-metallique'   => 'bulgaria-alternative-asia-metal-subcontracting',
        'pourquoi-externaliser-production-europe-est'           => 'why-outsource-production-eastern-europe',
        'bulgarie-vs-roumanie-sous-traitance-industrielle'      => 'bulgaria-vs-romania-industrial-subcontracting',
        'reduire-couts-fabrication-metallerie'                  => 'reduce-metalwork-manufacturing-costs',
        'comment-choisir-sous-traitant-industriel-fiable'       => 'how-to-choose-reliable-industrial-subcontractor',
    ];

    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('slug_en')->nullable()->unique()->after('slug');
        });

        foreach (self::SLUGS as $fr => $en) {
            DB::table('blog_posts')->where('slug', $fr)->update(['slug_en' => $en]);
        }
    }

    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn('slug_en');
        });
    }
};
