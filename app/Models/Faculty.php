<?php

namespace App\Models;

use Database\Factories\FacultyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['name', 'description'])]
class Faculty extends Model
{
    /** @use HasFactory<FacultyFactory> */
    use HasFactory;

    /**
     * @return HasMany<Department, $this>
     */
    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    /**
     * @return HasManyThrough<Course, Department, $this>
     */
    public function courses(): HasManyThrough
    {
        return $this->hasManyThrough(Course::class, Department::class);
    }

    /**
     * @return HasManyThrough<Student, Department, $this>
     */
    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(Student::class, Department::class);
    }

    /**
     * Scope the query to faculties matching the given term by name.
     *
     * @param  Builder<$this>  $query
     */
    public function scopeSearch(Builder $query, ?string $term): void
    {
        $query->when($term, fn (Builder $query, string $term) => $query->where('name', 'like', "%{$term}%"));
    }
}
