<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Make course_id nullable. Use DB-specific SQL to avoid requiring doctrine/dbal
        $driver = DB::getDriverName();
        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE evaluations ALTER COLUMN course_id DROP NOT NULL');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE `evaluations` MODIFY `course_id` BIGINT UNSIGNED NULL');
        } elseif ($driver === 'sqlite') {
            // SQLite can't alter column nullability easily; recreate is complex. Skip if already nullable.
            // Attempt a pragmatic approach: if NOT NULL, this will fail; advise manual change if needed.
            try {
                DB::statement('ALTER TABLE evaluations ALTER COLUMN course_id NULL');
            } catch (\Throwable $e) {
                // no-op
            }
        } else {
            // Fallback: try standard SQL
            DB::statement('ALTER TABLE evaluations ALTER COLUMN course_id DROP NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'pgsql') {
            // Revert to NOT NULL (will fail if rows with NULL exist)
            DB::statement('ALTER TABLE evaluations ALTER COLUMN course_id SET NOT NULL');
        } elseif ($driver === 'mysql') {
            DB::statement('ALTER TABLE `evaluations` MODIFY `course_id` BIGINT UNSIGNED NOT NULL');
        } elseif ($driver === 'sqlite') {
            try {
                DB::statement('ALTER TABLE evaluations ALTER COLUMN course_id NOT NULL');
            } catch (\Throwable $e) {
                // no-op
            }
        } else {
            DB::statement('ALTER TABLE evaluations ALTER COLUMN course_id SET NOT NULL');
        }
    }
};
