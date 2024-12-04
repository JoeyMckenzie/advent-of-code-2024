<?php

declare(strict_types=1);

namespace Tests\Solutions;

use JoeyMcKenzie\AdventOfCode\Solutions\Day2\Solution;

it('should compute the correct answer', function (): void {
    // Arrange & Act
    $result = Solution::part1();

    // Assert
    expect($result)->toBe(202);
});

it('should compute the correct answer for part 2', function (): void {
    // Arrange & Act
    $result = Solution::part2();

    // Assert
    expect($result)->toBe(271);
});
