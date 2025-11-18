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
        Schema::create('evaluation_questions', function (Blueprint $table) {
            $table->id();
            $table->string('section'); // e.g., Section A, Section B
            $table->unsignedInteger('number'); // position within section
            $table->text('text');
            $table->enum('type', ['scale', 'text'])->default('scale');
            $table->boolean('active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['section', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_questions');
    }
};
