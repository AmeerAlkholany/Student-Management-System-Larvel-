<?php

namespace App\Services;

use App\Models\Grade;

class GradeCalculationService
{
    /**
     * The inclusive lower bound of each letter grade, ordered from highest to lowest.
     *
     * @var array<string, int>
     */
    public const array LETTER_THRESHOLDS = [
        'A' => 90,
        'B' => 80,
        'C' => 70,
        'D' => 60,
    ];

    /**
     * The letter awarded when no threshold is met.
     */
    public const string FAILING_LETTER = 'F';

    /**
     * Build the derived grade attributes from the three weighted components.
     *
     * The components are already expressed on their weighted scale, so the
     * total is their sum out of 100.
     *
     * @return array{assignment: float, midterm: float, final: float, total: float, letter_grade: string}
     */
    public function calculate(float $assignment, float $midterm, float $final): array
    {
        $total = $this->total($assignment, $midterm, $final);

        return [
            'assignment' => round($assignment, 2),
            'midterm' => round($midterm, 2),
            'final' => round($final, 2),
            'total' => $total,
            'letter_grade' => $this->letterGrade($total),
        ];
    }

    /**
     * Sum the weighted components into a score out of 100.
     */
    public function total(float $assignment, float $midterm, float $final): float
    {
        return round($assignment + $midterm + $final, 2);
    }

    /**
     * Resolve the letter grade for a total score out of 100.
     */
    public function letterGrade(float $total): string
    {
        foreach (self::LETTER_THRESHOLDS as $letter => $threshold) {
            if ($total >= $threshold) {
                return $letter;
            }
        }

        return self::FAILING_LETTER;
    }

    /**
     * Determine the highest total a grade may reach.
     */
    public function maximumTotal(): int
    {
        return Grade::MAX_ASSIGNMENT + Grade::MAX_MIDTERM + Grade::MAX_FINAL;
    }
}
