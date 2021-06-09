<?php

namespace Climbx\Tests\Dotenv\Loader;

use Climbx\Dotenv\Loader\Loader;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Dotenv\Loader\Loader
 */
class LoaderTest extends TestCase
{
    public function testSimpleLoad()
    {
        $loader = new Loader();

        unset($_ENV['foo']);
        unset($_SERVER['foo']);

        $data = ['foo' => 'bar'];
        $loader->load($data);

        $this->assertArrayHasKey('foo', $_ENV);
        $this->assertArrayHasKey('foo', $_SERVER);

        $this->assertEquals('bar', $_ENV['foo']);
        $this->assertEquals('bar', $_SERVER['foo']);
    }

    public function testLoadNotOverwrite()
    {
        $loader = new Loader();

        unset($_ENV['foo']);
        unset($_SERVER['foo']);

        $_ENV['foo'] = 'bar';
        $_SERVER['foo'] = 'bar';

        $data = ['foo' => 'overwrite'];
        $loader->load($data);

        $this->assertArrayHasKey('foo', $_ENV);
        $this->assertArrayHasKey('foo', $_SERVER);

        $this->assertEquals('bar', $_ENV['foo']);
        $this->assertEquals('bar', $_SERVER['foo']);
    }

    public function testSimpleOverload()
    {
        $loader = new Loader();

        unset($_ENV['foo']);
        unset($_SERVER['foo']);

        $data = ['foo' => 'bar'];
        $loader->overLoad($data);

        $this->assertArrayHasKey('foo', $_ENV);
        $this->assertArrayHasKey('foo', $_SERVER);

        $this->assertEquals('bar', $_ENV['foo']);
        $this->assertEquals('bar', $_SERVER['foo']);
    }

    public function testOverloadOverwrite()
    {
        $loader = new Loader();

        unset($_ENV['foo']);
        unset($_SERVER['foo']);

        $_ENV['foo'] = 'bar';
        $_SERVER['foo'] = 'bar';

        $data = ['foo' => 'overwrite'];
        $loader->overLoad($data);

        $this->assertArrayHasKey('foo', $_ENV);
        $this->assertArrayHasKey('foo', $_SERVER);

        $this->assertEquals('overwrite', $_ENV['foo']);
        $this->assertEquals('overwrite', $_SERVER['foo']);
    }
}
