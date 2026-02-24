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
            $table->string('name_color')->default('#148bc9')->after('text_color');
            $table->string('data_color')->default('#000000')->after('name_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('id_card_templates', function (Blueprint $table) {
            $table->dropColumn(['name_color', 'data_color']);
        });
    }
};
