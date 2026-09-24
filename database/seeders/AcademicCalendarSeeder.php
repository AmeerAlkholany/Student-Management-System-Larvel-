<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AcademicCalendarSeeder extends Seeder
{
    /**
     * Seed the current academic year and its two semesters.
     */
    public function run(): void
    {
        $start = Carbon::create(now()->month >= 9 ? now()->year : now()->year - 1, 9, 1);

        $academicYear = AcademicYear::query()->create([
            'name' => $start->year.'/'.($start->year + 1),
            'start_date' => $start,
            'end_date' => $start->copy()->addYear()->subDay(),
            'is_current' => true,
        ]);

        Semester::query()->create([
            'academic_year_id' => $academicYear->id,
            'name' => 'First Semester',
            'start_date' => $start,
            'end_date' => $start->copy()->addMonths(4)->subDay(),
            'is_current' => false,
        ]);

        Semester::query()->create([
            'academic_year_id' => $academicYear->id,
            'name' => 'Second Semester',
            'start_date' => $start->copy()->addMonths(4),
            'end_date' => $start->copy()->addMonths(9)->subDay(),
            'is_current' => true,
        ]);
    }
}
