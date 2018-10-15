<?php

namespace Tests;

/**
 * Trait LoadFixture
 *
 * Load fixture.
 */
trait LoadFixture
{
    public function loadFixture(string $path): string
    {
        $path = __DIR__ . '/' . $path;
        return file_get_contents($path);
    }
}