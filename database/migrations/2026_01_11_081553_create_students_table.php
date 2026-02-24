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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('class');
            $table->string('section');
            $table->string('roll');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('blood_group')->nullable();
            $table->string('contact_no');
            $table->string('photo')->nullable();
            $table->string('signature')->nullable();
            $table->timestamps();

            // Unique constraint on roll per class+section in a school
            $table->unique(['school_id', 'class', 'section', 'roll']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
