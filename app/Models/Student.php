<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use App\Enums\Gender;
use App\Enums\StudentStatus;
use Database\Factories\StudentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property int $department_id
 * @property string $student_number
 * @property string $first_name
 * @property string $last_name
 * @property Carbon|null $date_of_birth
 * @property Gender|null $gender
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $photo
 * @property Carbon $enrollment_date
 * @property StudentStatus $status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read string $full_name
 * @property-read User $user
 * @property-read Department $department
 */
#[Fillable([
    'user_id',
    'department_id',
    'student_number',
    'first_name',
    'last_name',
    'date_of_birth',
    'gender',
    'phone',
    'address',
    'photo',
    'enrollment_date',
    'status',
])]
class Student extends Model
{
    /** @use HasFactory<StudentFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'enrollment_date' => 'date',
            'gender' => Gender::class,
            'status' => StudentStatus::class,
        ];
    }

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
     * @return HasManyThrough<Grade, Enrollment, $this>
     */
    public function grades(): HasManyThrough
    {
        return $this->hasManyThrough(Grade::class, Enrollment::class);
    }

    /**
     * Get the student's full name.
     *
     * @return Attribute<string, never>
     */
    protected function fullName(): Attribute
    {
        return Attribute::get(fn (): string => "{$this->first_name} {$this->last_name}");
    }

    /**
     * Determine whether the student may be enrolled into courses.
     */
    public function isEnrollable(): bool
    {
        return $this->status === StudentStatus::Active;
    }

    /**
     * Determine whether the student holds an enrollment in the given course.
     */
    public function isEnrolledIn(Course $course): bool
    {
        return $this->enrollments()->where('course_id', $course->id)->exists();
    }

    /**
     * Calculate the share of recorded sessions the student attended, as a percentage.
     *
     * Late arrivals count as attended; excused absences are still counted as
     * sessions the student did not attend.
     */
    public function attendancePercentage(?Course $course = null): float
    {
        $query = $this->attendances()->when($course, fn (Builder $query, Course $course) => $query->where('course_id', $course->id));

        $total = (clone $query)->count();

        if ($total === 0) {
            return 0.0;
        }

        $attended = (clone $query)->whereIn('status', AttendanceStatus::attended())->count();

        return round($attended / $total * 100, 2);
    }

    /**
     * Scope the query to students matching the given term by student number,
     * first name, last name, or the email address of the linked user account.
     *
     * @param  Builder<$this>  $query
     */
    public function scopeSearch(Builder $query, ?string $term): void
    {
        $query->when($term, function (Builder $query, string $term): void {
            $query->where(function (Builder $query) use ($term): void {
                $query->where('student_number', 'like', "%{$term}%")
                    ->orWhere('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%")
                    ->orWhereHas('user', fn (Builder $query) => $query->where('email', 'like', "%{$term}%"));
            });
        });
    }

    /**
     * Apply the index filters that arrive as query parameters.
     *
     * @param  Builder<$this>  $query
     * @param  array{search?: string|null, department?: int|string|null, status?: string|null}  $filters
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->search($filters['search'] ?? null)
            ->when(
                $filters['department'] ?? null,
                fn (Builder $query, int|string $department) => $query->where('department_id', $department),
            )
            ->when(
                $filters['status'] ?? null,
                fn (Builder $query, string $status) => $query->where('status', $status),
            );
    }
}
