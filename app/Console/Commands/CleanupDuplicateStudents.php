<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class CleanupDuplicateStudents extends Command
{
    protected $signature = 'students:cleanup-duplicates';
    protected $description = 'Remove duplicate Student records keeping the oldest one';

    public function handle()
    {
        $this->info('Starting cleanup of duplicate Student records...');
        
        // Find users with multiple Student records
        $duplicateUserIds = Student::withTrashed()
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('count(*) > 1')
            ->pluck('user_id');
            
        if ($duplicateUserIds->isEmpty()) {
            $this->info('No duplicate Student records found.');
            return;
        }
        
        $totalDeleted = 0;
        
        foreach ($duplicateUserIds as $userId) {
            $students = Student::withTrashed()
                ->where('user_id', $userId)
                ->orderBy('created_at')
                ->get();
                
            // Keep the first (oldest) record, delete the rest
            $keep = $students->first();
            $toDelete = $students->skip(1);
            
            $this->info("User ID {$userId} has {$students->count()} Student records. Keeping ID {$keep->id}, deleting " . $toDelete->count() . " duplicates.");
            
            foreach ($toDelete as $student) {
                $this->line("  - Deleting Student ID {$student->id}");
                $student->forceDelete();
                $totalDeleted++;
            }
        }
        
        $this->info("Cleanup completed. Deleted {$totalDeleted} duplicate Student records.");
        $this->info('Remaining Student records: ' . Student::withTrashed()->count());
    }
}
