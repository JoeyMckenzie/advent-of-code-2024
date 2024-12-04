<?php

declare(strict_types=1);

namespace JoeyMcKenzie\AdventOfCode\Solutions\Day3;

use JoeyMcKenzie\AdventOfCode\Utilities\FileHelper;

/**
 * @phpstan-type MulCommand array{
 *   type: 'mul',
 *   position: int,
 *   nums: array{0: int, 1: int}
 * }
 * @phpstan-type ControlCommand array{
 *   type: 'do'|'dont',
 *   position: int
 * }
 * @phpstan-type Command MulCommand|ControlCommand
 */
final class Solution
{
    private const string MUL_PATTERN = '/mul\((\d{1,3}),(\d{1,3})\)/';

    private const string COMMAND_PATTERN = '/(?:do|don\'t)\(\)/';

    public static function part1(): int
    {
        $input = FileHelper::readFile(__DIR__.'/input.txt');

        return self::sumMultiplicationCommands($input);
    }

    public static function part2(): int
    {
        $input = FileHelper::readFile(__DIR__.'/input.txt');

        return self::sumMultiplicationCommandsWithControls($input);
    }

    private static function sumMultiplicationCommands(string $input): int
    {
        $sum = 0;
        $matchesFound = preg_match_all(self::MUL_PATTERN, $input, $matches, PREG_SET_ORDER);

        if ($matchesFound !== false && $matchesFound > 0) {
            return array_reduce($matches, function (int $carry, array $match): int {
                // $match[0] is the full match, $match[1] is first number, $match[2] is second number
                $product = intval($match[1]) * intval($match[2]);

                return $carry + $product;
            }, 0);
        }

        return $sum;
    }

    private static function sumMultiplicationCommandsWithControls(string $input): int
    {
        /** @var array<int, array{type: "mul"|"do"|"dont", position: int, nums?: ?array{0: int, 1: int}}> $commands */
        $commands = [];
        $matchesFound = preg_match_all(self::MUL_PATTERN, $input, $mulMatches, PREG_OFFSET_CAPTURE);

        // Find multiplication commands
        if ($matchesFound !== false && $matchesFound > 0) {
            foreach ($mulMatches[0] as $index => $match) {
                $commands[] = [
                    'type' => 'mul',
                    'position' => $match[1],
                    'nums' => [
                        intval($mulMatches[1][$index][0]),
                        intval($mulMatches[2][$index][0]),
                    ],
                ];
            }
        }

        // Find control commands
        $commandMatches = preg_match_all(self::COMMAND_PATTERN, $input, $controlMatches, PREG_OFFSET_CAPTURE);

        if ($commandMatches !== false && $commandMatches > 0) {
            foreach ($controlMatches[0] as $match) {
                $commands[] = [
                    'type' => $match[0] === 'do()' ? 'do' : 'dont',
                    'position' => $match[1],
                ];
            }
        }

        // Sort commands by position to process them in order
        usort($commands, fn (array $a, array $b): int => $a['position'] <=> $b['position']);

        // Process commands in order
        $enabled = true; // Multiplications are enabled at start
        $sum = 0;

        foreach ($commands as $command) {
            switch ($command['type']) {
                case 'mul':
                    if ($enabled && isset($command['nums'])) {
                        $sum += $command['nums'][0] * $command['nums'][1];
                    }
                    break;
                case 'do':
                    $enabled = true;
                    break;
                case 'dont':
                    $enabled = false;
                    break;
            }
        }

        return $sum;
    }
}
