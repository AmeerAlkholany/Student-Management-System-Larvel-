<?php

namespace App\Models;

use Database\Factories\SemesterFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $academic_year_id
 * @property string $name
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property bool $is_current
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read AcademicYear $academicYear
 */
#[Fillable(['academic_year_id', 'name', 'start_date', 'end_date', 'is_current'])]
class Semester extends Model
{
    /** @use HasFactory<SemesterFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_current' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<AcademicYear, $this>
     */
    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    /**
     * @return HasMany<Enrollment, $this>
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Scope the query to the semester flagged as current.
     *
     * @param  Builder<$this>  $query
     */
    public function scopeCurrent(Builder $query): void
    {
        $query->where('is_current', true);
    }
}
