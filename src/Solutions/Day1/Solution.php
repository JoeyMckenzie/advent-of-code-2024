<?php

declare(strict_types=1);

namespace JoeyMcKenzie\AdventOfCode\Solutions\Day1;

use JoeyMcKenzie\AdventOfCode\Utilities\FileHelper;

final class Solution
{
    public static function part1(): int
    {
        $fileContents = FileHelper::readFile(__DIR__.'/input.txt');
        [$leftList, $rightList] = self::loadListData($fileContents);

        return self::calculateTotalDistance($leftList, $rightList);
    }

    public static function part2(): int
    {
        $fileContents = FileHelper::readFile(__DIR__.'/input.txt');
        [$leftList, $rightList] = self::loadListData($fileContents);

        return self::calculateSimilarityScore($leftList, $rightList);

    }

    /**
     * @return array<int, int[]>
     */
    private static function loadListData(string $input): array
    {
        // Split input into lines and parse into two arrays
        $lines = explode("\n", trim($input));
        $leftList = [];
        $rightList = [];

        foreach ($lines as $line) {
            // Split each line into left and right numbers
            $matches = preg_split('/\s+/', trim($line));

            if (is_array($matches)) {
                [$left, $right] = $matches;
                $leftList[] = (int) $left;
                $rightList[] = (int) $right;
            }
        }

        return [$leftList, $rightList];
    }

    /**
     * @param  int[]  $leftList
     * @param  int[]  $rightList
     */
    private static function calculateTotalDistance(array $leftList, array $rightList): int
    {
        sort($leftList);
        sort($rightList);
        $index = 0;

        return array_reduce($leftList, function (int $carry, int $value) use (&$index, $rightList): float|int {
            $distance = abs($value - $rightList[$index]);
            $carry += $distance;
            $index++;

            return $carry;
        }, 0);
    }

    /**
     * @param  int[]  $leftList
     * @param  int[]  $rightList
     */
    private static function calculateSimilarityScore(array $leftList, array $rightList): int
    {
        $index = 0;

        return array_reduce($leftList, function (int $carry, int $value) use (&$index, $rightList): int {
            $matchedValues = array_filter($rightList, fn (int $rightValue): bool => $rightValue === $value);
            $score = count($matchedValues) * $value;
            $carry += $score;
            $index++;

            return $carry;
        }, 0);
    }
}
