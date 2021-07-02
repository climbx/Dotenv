<?php

namespace Climbx\Dotenv;

/**
 * @package Climbx\Dotenv
 */
interface DotenvInterface
{
    /**
     * Reads, parses and write a .env file data in the $_ENV & $_SERVER super-global variables.
     *
     * If a variable already exists, it is ignored.
     *
     * @param string $filePath
     */
    public function load(string $filePath): void;

    /**
     * Reads, parses and write a .env file data in the $_ENV & $_SERVER super-global variables.
     *
     * If a variable already exists, it is overridden.
     *
     * @param string $filePath
     */
    public function overload(string $filePath): void;
}
