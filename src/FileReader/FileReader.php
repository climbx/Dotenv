<?php

namespace Climbx\Dotenv\FileReader;

class FileReader implements FileReaderInterface
{
    /**
     * @param string $path
     *
     * @return array
     *
     * @throws FileErrorException
     */
    public function getContentAsArray(string $path): array
    {
        $this->checkFilePath($path);

        $fileAsArray = file($path);

        if (false === $fileAsArray) {
            throw new FileErrorException(sprintf('The .env file could not be read. File path: "%s"', $path));
        }

        return $fileAsArray;
    }

    /**
     * @param $filePath
     *
     * @throws FileErrorException
     */
    private function checkFilePath($filePath)
    {
        if (!is_readable($filePath)) {
            throw new FileErrorException(
                sprintf('The .env file do not exists or is not readable. File path: "%s"', $filePath)
            );
        }
    }
}
