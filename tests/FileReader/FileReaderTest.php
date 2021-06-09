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

        $tmpdir = sys_get_temp_dir();
        $filename = tempnam($tmpdir, 'env');

        $fileContent = $fileReader->getContentAsArray($filename);

        $this->assertIsArray($fileContent);

        unlink($filename);
    }

    public function testFileContentHasEmptyLines()
    {
        $fileReader = new FileReader();

        $tmpdir = sys_get_temp_dir();
        $filePath = tempnam($tmpdir, 'env');
        file_put_contents($filePath, "foo=bar\n\nfoo=baz");

        $fileContent = $fileReader->getContentAsArray($filePath);

        $hasEmptyLine = false;
        foreach ($fileContent as $line) {
            if ($line === "\n") {
                $hasEmptyLine = true;
            }
        }

        $this->assertTrue($hasEmptyLine);

        unlink($filePath);
    }
}
