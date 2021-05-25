<?php

namespace Climbx\Dotenv\Parser;

interface ParserInterface
{
    /**
     * Should return an array of key => value from a .env file content
     *
     * @param array $fileContent Array of the file lines.
     *
     * @return array
     */
    public function getParsedData(array $fileContent): array;
}
