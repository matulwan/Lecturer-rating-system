<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('user_code')->unique();
            $table->string('name');
            $table->string('ic_number')->unique();
            $table->string('salary_number')->nullable();
            $table->string('password');
            $table->enum('role', ['student', 'lecturer', 'super_admin'])->default('student');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};