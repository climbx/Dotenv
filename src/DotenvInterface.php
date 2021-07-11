<?php

namespace Climbx\Dotenv;

use Climbx\Dotenv\Bag\DotenvBag;

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

    /**
     * Returns a parsed .env data bag.
     *
     * If the .env file is missing, the method returns false.
     *
     * @param string $filePath
     *
     * @return DotenvBag|false
     */
    public function getEnvData(string $filePath): DotenvBag | false;
}
