<?php

namespace App\Models;

use App\Enums\EnrollmentStatus;
use Database\Factories\EnrollmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $student_id
 * @property int $course_id
 * @property int $semester_id
 * @property Carbon $enrolled_at
 * @property EnrollmentStatus $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Student $student
 * @property-read Course $course
 * @property-read Semester $semester
 * @property-read Grade|null $grade
 */
#[Fillable(['student_id', 'course_id', 'semester_id', 'enrolled_at', 'status'])]
class Enrollment extends Model
{
    /** @use HasFactory<EnrollmentFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'enrolled_at' => 'datetime',
            'status' => EnrollmentStatus::class,
        ];
    }

    /**
     * @return BelongsTo<Student, $this>
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * @return BelongsTo<Course, $this>
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * @return BelongsTo<Semester, $this>
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * @return HasOne<Grade, $this>
     */
    public function grade(): HasOne
    {
        return $this->hasOne(Grade::class);
    }

    /**
     * Apply the index filters that arrive as query parameters.
     *
     * @param  Builder<$this>  $query
     * @param  array{student?: int|string|null, course?: int|string|null, semester?: int|string|null, status?: string|null}  $filters
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when(
            $filters['student'] ?? null,
            fn (Builder $query, int|string $student) => $query->where('student_id', $student),
        )->when(
            $filters['course'] ?? null,
            fn (Builder $query, int|string $course) => $query->where('course_id', $course),
        )->when(
            $filters['semester'] ?? null,
            fn (Builder $query, int|string $semester) => $query->where('semester_id', $semester),
        )->when(
            $filters['status'] ?? null,
            fn (Builder $query, string $status) => $query->where('status', $status),
        );
    }
}
