<?php

namespace Climbx\Dotenv\FileReader;

interface FileReaderInterface
{
    /**
     * Should return the content of a file as an array of lines
     *
     * @param string $path
     *
     * @return array
     */
    public function getContentAsArray(string $path): array;
}
