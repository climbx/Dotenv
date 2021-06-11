<?php

namespace Climbx\Dotenv\Tests;

use Climbx\Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Dotenv\Dotenv
 * @covers \Climbx\Dotenv\FileReader\FileReader
 * @covers \Climbx\Dotenv\Loader\Loader
 * @covers \Climbx\Dotenv\Parser\Parser
 * @covers \Climbx\Dotenv\Parser\LineParser
 * @covers \Climbx\Dotenv\Parser\Line
 * @covers \Climbx\Dotenv\Parser\KeyParser
 * @covers \Climbx\Dotenv\Parser\ValueParser
 * @covers \Climbx\Dotenv\Parser\ValueParserState
 * @covers \Climbx\Dotenv\Parser\ValueStepStartParser
 * @covers \Climbx\Dotenv\Parser\ValueStepMiddleParser
 * @covers \Climbx\Dotenv\Parser\ValueStepMiddleReferenceParser
 * @covers \Climbx\Dotenv\Parser\ValueStepEndParser
 * @covers \Climbx\Dotenv\Parser\Reference
 * @covers \Climbx\Dotenv\Parser\Char
 */
class DotenvTest extends TestCase
{
    public function testLoad()
    {
        $dotenv = new Dotenv();

        unset($_ENV['foo']);
        unset($_ENV['baz']);
        unset($_SERVER['foo']);
        unset($_SERVER['baz']);

        $_ENV['baz'] = 'origin';
        $_SERVER['baz'] = 'origin';

        $tmpdir = sys_get_temp_dir();
        $filePath = tempnam($tmpdir, 'env');
        file_put_contents($filePath, "foo=bar\nbaz=overridden");

        $dotenv->load($filePath);

        unlink($filePath);

        $this->assertArrayHasKey('foo', $_ENV);
        $this->assertArrayHasKey('foo', $_SERVER);

        $this->assertEquals('bar', $_ENV['foo']);
        $this->assertEquals('bar', $_SERVER['foo']);

        $this->assertEquals('origin', $_ENV['baz']);
        $this->assertEquals('origin', $_SERVER['baz']);

        unset($_ENV['foo']);
        unset($_ENV['baz']);
        unset($_SERVER['foo']);
        unset($_SERVER['baz']);
    }

    public function testLoadEmptyFile()
    {
        $env = $_ENV;
        $server = $_SERVER;

        $dotenv = new Dotenv();

        $tmpdir = sys_get_temp_dir();
        $filePath = tempnam($tmpdir, 'env');

        $dotenv->load($filePath);

        unlink($filePath);

        $this->assertEquals($env, $_ENV);
        $this->assertEquals($server, $_SERVER);
    }

    public function testLoadEmptyData()
    {
        $env = $_ENV;
        $server = $_SERVER;

        $dotenv = new Dotenv();

        $tmpdir = sys_get_temp_dir();
        $filePath = tempnam($tmpdir, 'env');
        file_put_contents($filePath, "\n\n# This is a comment\n# This is another comment");

        $dotenv->load($filePath);

        unlink($filePath);

        $this->assertEquals($env, $_ENV);
        $this->assertEquals($server, $_SERVER);
    }

    public function testOverload()
    {
        $dotenv = new Dotenv();

        unset($_ENV['foo']);
        unset($_ENV['baz']);
        unset($_SERVER['foo']);
        unset($_SERVER['baz']);

        $_ENV['baz'] = 'origin';
        $_SERVER['baz'] = 'origin';

        $tmpdir = sys_get_temp_dir();
        $filePath = tempnam($tmpdir, 'env');
        file_put_contents($filePath, "foo=bar\nbaz=overridden");

        $dotenv->overload($filePath);

        unlink($filePath);

        $this->assertArrayHasKey('foo', $_ENV);
        $this->assertArrayHasKey('foo', $_SERVER);

        $this->assertEquals('bar', $_ENV['foo']);
        $this->assertEquals('bar', $_SERVER['foo']);

        $this->assertEquals('overridden', $_ENV['baz']);
        $this->assertEquals('overridden', $_SERVER['baz']);

        unset($_ENV['foo']);
        unset($_ENV['baz']);
        unset($_SERVER['foo']);
        unset($_SERVER['baz']);
    }

    public function testOverloadEmptyFile()
    {
        $env = $_ENV;
        $server = $_SERVER;

        $dotenv = new Dotenv();

        $tmpdir = sys_get_temp_dir();
        $filePath = tempnam($tmpdir, 'env');

        $dotenv->overload($filePath);

        unlink($filePath);

        $this->assertEquals($env, $_ENV);
        $this->assertEquals($server, $_SERVER);
    }

    public function testOverloadEmptyData()
    {
        $env = $_ENV;
        $server = $_SERVER;

        $dotenv = new Dotenv();

        $tmpdir = sys_get_temp_dir();
        $filePath = tempnam($tmpdir, 'env');
        file_put_contents($filePath, "\n\n# This is a comment\n# This is another comment");

        $dotenv->overload($filePath);

        unlink($filePath);

        $this->assertEquals($env, $_ENV);
        $this->assertEquals($server, $_SERVER);
    }
}
