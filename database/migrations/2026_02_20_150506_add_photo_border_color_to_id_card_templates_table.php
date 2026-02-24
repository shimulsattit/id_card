<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('id_card_templates', function (Blueprint $table) {
            $table->string('photo_border_color')->default('#000000')->after('data_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('id_card_templates', function (Blueprint $table) {
            $table->dropColumn('photo_border_color');
        });
    }
};
