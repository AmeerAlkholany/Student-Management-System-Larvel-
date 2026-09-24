<?php

namespace App\Models;

use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $department_id
 * @property int|null $instructor_id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property int $credit_hours
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Department $department
 * @property-read Instructor|null $instructor
 */
#[Fillable(['department_id', 'instructor_id', 'code', 'name', 'description', 'credit_hours'])]
class Course extends Model
{
    /** @use HasFactory<CourseFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'credit_hours' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return BelongsTo<Instructor, $this>
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    /**
     * @return HasMany<Enrollment, $this>
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * @return HasMany<Attendance, $this>
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * @return BelongsToMany<Student, $this>
     */
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'enrollments')
            ->withPivot(['id', 'semester_id', 'status', 'enrolled_at'])
            ->withTimestamps();
    }

    /**
     * Determine whether the given instructor teaches this course.
     */
    public function isTaughtBy(Instructor $instructor): bool
    {
        return $this->instructor_id === $instructor->id;
    }

    /**
     * Scope the query to courses matching the given term by code or name.
     *
     * @param  Builder<$this>  $query
     */
    public function scopeSearch(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('code', 'like', "%{$term}%")
                    ->orWhere('name', 'like', "%{$term}%");
            });
        });
    }

    /**
     * Apply the index filters that arrive as query parameters.
     *
     * @param  Builder<$this>  $query
     * @param  array{search?: string|null, department?: int|string|null, instructor?: int|string|null}  $filters
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->search($filters['search'] ?? null)
            ->when(
                $filters['department'] ?? null,
                fn (Builder $query, int|string $department) => $query->where('department_id', $department),
            )
            ->when(
                $filters['instructor'] ?? null,
                fn (Builder $query, int|string $instructor) => $query->where('instructor_id', $instructor),
            );
    }
}
