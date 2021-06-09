<?php

namespace Climbx\Tests\Dotenv\FileReader;

use Climbx\Dotenv\Exception\FileErrorException;
use Climbx\Dotenv\FileReader\FileReader;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Dotenv\FileReader\FileReader
 */
class FileReaderTest extends TestCase
{
    public function testBadFilePath()
    {
        $this->expectException(FileErrorException::class);
        $this->expectExceptionMessage('The .env file do not exists or is not readable. File path: "/path/to/my/.env"');

        $fileReader = new FileReader();
        $fileReader->getContentAsArray('/path/to/my/.env');
    }

    public function testGoodFilePath()
    {
        $fileReader = new FileReader();
        $fileContent = $fileReader->getContentAsArray(__FILE__);

        $this->assertIsArray($fileContent);
    }

    public function testFileContentHasEmptyLines()
    {
        $fileReader = new FileReader();
        $fileContent = $fileReader->getContentAsArray(__FILE__);

        $hasEmptyLine = false;
        foreach ($fileContent as $line) {
            if ($line === "\n") {
                $hasEmptyLine = true;
            }
        }

        $this->assertTrue($hasEmptyLine);
    }
}
