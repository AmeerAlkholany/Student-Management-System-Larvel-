<?php

namespace App\Models;

use Database\Factories\InstructorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $department_id
 * @property string $employee_number
 * @property string|null $phone
 * @property string|null $specialization
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @property-read Department $department
 */
#[Fillable(['user_id', 'department_id', 'employee_number', 'phone', 'specialization'])]
class Instructor extends Model
{
    /** @use HasFactory<InstructorFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * @return HasMany<Course, $this>
     */
    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Determine whether this instructor is assigned to the given course.
     */
    public function teaches(Course $course): bool
    {
        return $course->instructor_id === $this->id;
    }

    /**
     * Determine whether the student is enrolled in any course this instructor teaches.
     */
    public function teachesStudent(Student $student): bool
    {
        return $this->courses()
            ->whereHas('enrollments', fn (Builder $query) => $query->where('student_id', $student->id))
            ->exists();
    }

    /**
     * Scope the query to instructors matching the given term by employee number,
     * specialization, or the name and email address of the linked user account.
     *
     * @param  Builder<$this>  $query
     */
    public function scopeSearch(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('employee_number', 'like', "%{$term}%")
                    ->orWhere('specialization', 'like', "%{$term}%")
                    ->orWhereHas('user', function (Builder $query) use ($term): void {
                        $query->where('name', 'like', "%{$term}%")
                            ->orWhere('email', 'like', "%{$term}%");
                    });
            });
        });
    }
}
