<?php

declare(strict_types=1);

namespace JoeyMcKenzie\AdventOfCode\Contracts;

/**
 * @template TAnswer
 */
interface SolvableChallenge
{
    /**
     * @return TAnswer
     */
    public static function part1(): mixed;
}
