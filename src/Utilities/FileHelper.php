<?php

declare(strict_types=1);

namespace JoeyMcKenzie\AdventOfCode\Utilities;

use RuntimeException;

final class FileHelper
{
    public static function readFile(string $path): string
    {
        $contents = file_get_contents($path);

        if ($contents === false) {
            throw new RuntimeException('Unable to read file: '.$path);
        }

        return $contents;
    }

    /**
     * @return string[]
     */
    public static function readFileContentsToArray(string $path): array
    {
        $contents = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($contents === false) {
            throw new RuntimeException('Unable to read file: '.$path);
        }

        return $contents;
    }
}
