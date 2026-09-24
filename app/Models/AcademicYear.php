<?php

namespace App\Models;

use Database\Factories\AcademicYearFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property bool $is_current
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'start_date', 'end_date', 'is_current'])]
class AcademicYear extends Model
{
    /** @use HasFactory<AcademicYearFactory> */
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
     * @return HasMany<Semester, $this>
     */
    public function semesters(): HasMany
    {
        return $this->hasMany(Semester::class);
    }

    /**
     * Scope the query to the academic year flagged as current.
     *
     * @param  Builder<$this>  $query
     */
    public function scopeCurrent(Builder $query): void
    {
        $query->where('is_current', true);
    }
}
