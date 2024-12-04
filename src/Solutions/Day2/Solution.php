<?php

declare(strict_types=1);

namespace JoeyMcKenzie\AdventOfCode\Solutions\Day2;

use JoeyMcKenzie\AdventOfCode\Utilities\FileHelper;

final class Solution
{
    public static function part1(): int
    {
        $fileContents = FileHelper::readFile(__DIR__.'/input.txt');

        return self::countSafeReports($fileContents);
    }

    public static function part2(): int
    {
        $fileContents = FileHelper::readFile(__DIR__.'/input.txt');

        return self::countSafeReportsWithDampening($fileContents);
    }

    private static function countSafeReports(string $input): int
    {
        $reports = explode("\n", trim($input));

        return array_reduce($reports, function (int $count, string $report): int {
            $levels = array_map(fn (string $level): int => intval($level), explode(' ', $report));
            $reportScore = self::isReportSafe($levels) ? 1 : 0;

            return $count + $reportScore;
        }, 0);
    }

    /**
     * @param  int[]  $levels
     */
    private static function isReportSafe(array $levels): bool
    {
        // Check if sequence is monotonic and differences are within bounds
        /** @var ?bool $isIncreasing */
        $isIncreasing = null;
        $counter = count($levels);

        for ($i = 1; $i < $counter; $i++) {
            // Check for adjacent duplicates
            if ($levels[$i] === $levels[$i - 1]) {
                return false;
            }

            $diff = $levels[$i] - $levels[$i - 1];
            $absDiff = abs($diff);

            // Check if difference is within 1-3 range
            if ($absDiff < 1 || $absDiff > 3) {
                return false;
            }

            // Determine and validate direction
            if ($isIncreasing === null) {
                $isIncreasing = $diff > 0;
            } elseif ($isIncreasing && $diff < 0 || ! $isIncreasing && $diff > 0) {
                return false;
            }
        }

        return true;
    }

    private static function countSafeReportsWithDampening(string $input): int
    {
        $reports = explode("\n", trim($input));

        return array_reduce($reports, function (int $count, string $report): int {
            $levels = array_map(fn (string $value): int => intval($value), explode(' ', $report));
            $safetyScoreWithDampening = self::isReportSafeWithDampener($levels) ? 1 : 0;

            return $count + $safetyScoreWithDampening;
        }, 0);
    }

    /**
     * @param  int[]  $levels
     */
    private static function isReportSafeWithDampener(array $levels): bool
    {
        // First check if it's safe without dampener
        if (self::isReportSafe($levels)) {
            return true;
        }
        // Roll through each level, removing one value from each level and testing if it's still a safe report
        $counter = count($levels);

        // Roll through each level, removing one value from each level and testing if it's still a safe report
        for ($i = 0; $i < $counter; $i++) {
            /** @var int[] $testLevels */
            $testLevels = array_merge(
                array_slice($levels, 0, $i),
                array_slice($levels, $i + 1)
            );

            if (self::isReportSafe($testLevels)) {
                return true;
            }
        }

        return false;
    }
}
