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
        Schema::create('id_card_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('background_image'); // Path to uploaded image
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade'); // Null for system defaults
            $table->enum('type', ['student', 'teacher'])->default('student');
            $table->string('text_color')->default('#000000'); // Default text color
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('id_card_templates');
    }
};
