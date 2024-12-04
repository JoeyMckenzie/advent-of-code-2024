<?php

declare(strict_types=1);

namespace JoeyMcKenzie\AdventOfCode\Solutions\Day4;

use JoeyMcKenzie\AdventOfCode\Utilities\FileHelper;

/**
 * Not proud of myself, had to rubber duck with Claude on this one...
 */
final class Solution
{
    public static function part1(): int
    {
        $fileContents = FileHelper::readFile(__DIR__.'/input.txt');

        // Parse the file input into a giant grid we can traverse based on the direction we want to go from the current element
        $grid = array_map(
            fn (string $line) => str_split(trim($line)),
            explode("\n", $fileContents)
        );

        return self::countXmas($grid);
    }

    public static function part2(): int
    {
        $fileContents = FileHelper::readFile(__DIR__.'/input.txt');

        // Parse the file input into a giant grid we can traverse based on the direction we want to go from the current element
        $grid = array_map(
            fn (string $line) => str_split(trim($line)),
            explode("\n", $fileContents)
        );

        return self::countMas($grid);
    }

    /**
     * @param  array<int, array<int, string>>  $grid
     */
    private static function countXmas(array $grid): int
    {
        // Keep a count for bounds checking, along with a rolling total of the number of 'XMAS' words we find
        $rows = count($grid);
        $cols = count($grid[array_key_first($grid)]);
        $count = 0;

        // All 8 directions
        $directions = [
            [0, 1],   // right
            [1, 0],   // down
            [1, 1],   // diagonal down-right
            [1, -1],  // diagonal down-left
            [0, -1],  // left
            [-1, 0],  // up
            [-1, 1],  // diagonal up-right
            [-1, -1], // diagonal up-left
        ];

        // We'll check each direction for the target word and continue on if we find matches
        $target = ['X', 'M', 'A', 'S'];

        // Roll through the rows of the grid
        for ($row = 0; $row < $rows; $row++) {
            // Roll through the columns of the grid
            for ($col = 0; $col < $cols; $col++) {
                // Check each direction we can traverse based on the current grid element
                foreach ($directions as [$dx, $dy]) {
                    // Assume we've found a match that we'll check based on the direction later
                    $found = true;
                    $currentRow = $row;
                    $currentCol = $col;

                    // We need to check for each character in XMAS if the current direction we've stepped is a match
                    // If so, we'll keep going until we've found an invalid character
                    foreach ($target as $character) {
                        $gridPositionIsOutOfBounds = $currentRow < 0 || $currentRow >= $rows || $currentCol < 0 || $currentCol >= $cols;

                        // If we're out of places to go, assume we don't have a match and continue onto the next grid element
                        if ($gridPositionIsOutOfBounds) {
                            $found = false;
                            break;
                        }

                        // If there's no possible match, continue on to the next grid element
                        if ($grid[$currentRow][$currentCol] !== $character) {
                            $found = false;
                            break;
                        }

                        // Assume we found a match and check the next target character
                        $currentRow += $dx;
                        $currentCol += $dy;
                    }

                    // If we've rolled through each of the characters we're looking for in XMAS for the current direction,
                    // assume we found a match/hit and add it to our rolling counter
                    if ($found) {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }

    /**
     * @param  array<int, array<int, string>>  $grid
     */
    private static function countMas(array $grid): int
    {
        // Keep a count for bounds checking, along with a rolling total of the number of 'XMAS' words we find
        $rows = count($grid);
        $cols = count($grid[array_key_first($grid)]);
        $count = 0;

        // Check all possible diagonals
        $diagonals = [
            [[1, 1], [-1, -1]],   // down-right and up-left
            [[1, -1], [-1, 1]],   // down-left and up-right
            [[1, 1], [-1, 1]],    // down-right and up-right
            [[1, -1], [-1, -1]],  // down-left and up-left
            [[-1, 1], [-1, -1]],  // up-right and up-left
            [[1, 1], [1, -1]],    // down-right and down-left
        ];

        // Roll through the grid by column and row
        for ($row = 1; $row < $rows - 1; $row++) {
            for ($col = 1; $col < $cols - 1; $col++) {
                // Check if center is 'A', if not keep going until we find one
                if ($grid[$row][$col] !== 'A') {
                    continue;
                }

                // We have a center point now, so roll through each potential direction we can go to check if there's a match
                foreach ($diagonals as [$dir1, $dir2]) {
                    [$dx1, $dy1] = $dir1;
                    [$dx2, $dy2] = $dir2;

                    $matchFoundInFirstDirection = self::checkMas($grid, $row - $dx1, $col - $dy1, $dx1, $dy1);
                    $matchFoundInSecondDirection = self::checkMas($grid, $row - $dx2, $col - $dy2, $dx2, $dy2);

                    // We found a match in both directions, so add it to our rolling count
                    if ($matchFoundInFirstDirection && $matchFoundInSecondDirection) {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }

    /**
     * @param  array<int, array<int, string>>  $grid
     */
    private static function checkMas(array $grid, int $startRow, int $startCol, int $dx, int $dy): bool
    {
        $rows = count($grid);
        $cols = count($grid[array_key_first($grid)]);
        $target = ['M', 'A', 'S'];

        $currentRow = $startRow;
        $currentCol = $startCol;

        foreach ($target as $character) {
            $gridPositionIsOutOfBounds = $currentRow < 0 || $currentRow >= $rows || $currentCol < 0 || $currentCol >= $cols;

            if ($gridPositionIsOutOfBounds) {
                return false;
            }

            if ($grid[$currentRow][$currentCol] !== $character) {
                return false;
            }

            $currentRow += $dx;
            $currentCol += $dy;
        }

        return true;
    }
}
