<?php

namespace App\Models;

use Database\Factories\GradeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $enrollment_id
 * @property float $assignment
 * @property float $midterm
 * @property float $final
 * @property float $total
 * @property string|null $letter_grade
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Enrollment $enrollment
 */
#[Fillable(['enrollment_id', 'assignment', 'midterm', 'final', 'total', 'letter_grade'])]
class Grade extends Model
{
    /** @use HasFactory<GradeFactory> */
    use HasFactory;

    /**
     * The highest score each component may receive.
     */
    public const int MAX_ASSIGNMENT = 10;

    public const int MAX_MIDTERM = 30;

    public const int MAX_FINAL = 60;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'assignment' => 'float',
            'midterm' => 'float',
            'final' => 'float',
            'total' => 'float',
        ];
    }

    /**
     * @return BelongsTo<Enrollment, $this>
     */
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
}
