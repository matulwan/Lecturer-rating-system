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
        Schema::table('surveys', function (Blueprint $table) {
            // Drop the old fixed question columns
            $table->dropColumn(['question_1_rating', 'question_2_rating', 'question_3_rating']);
            
            // Add new JSON column to store dynamic CLO ratings
            $table->json('clo_ratings')->after('course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            // Remove the JSON column
            $table->dropColumn('clo_ratings');
            
            // Restore the old fixed question columns
            $table->integer('question_1_rating')->after('course_id');
            $table->integer('question_2_rating')->after('question_1_rating');
            $table->integer('question_3_rating')->after('question_2_rating');
        });
    }
};
