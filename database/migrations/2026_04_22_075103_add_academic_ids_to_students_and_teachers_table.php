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
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('academic_class_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('set null');
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->foreignId('academic_class_id')->nullable()->constrained()->onDelete('set null'); // Class teacher relationship
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['academic_class_id']);
            $table->dropForeign(['section_id']);
            $table->dropColumn(['academic_class_id', 'section_id']);
        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->dropForeign(['academic_class_id']);
            $table->dropForeign(['section_id']);
            $table->dropColumn(['academic_class_id', 'section_id']);
        });
    }
};
