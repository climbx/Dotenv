<?php

namespace Climbx\Dotenv;

use Climbx\Dotenv\FileReader\FileReader;
use Climbx\Dotenv\FileReader\FileReaderInterface;
use Climbx\Dotenv\Loader\Loader;
use Climbx\Dotenv\Loader\LoaderInterface;
use Climbx\Dotenv\Parser\Parser;
use Climbx\Dotenv\Parser\ParserInterface;

class Dotenv implements DotenvInterface
{
    private FileReaderInterface $fileReader;
    private ParserInterface $parser;
    private LoaderInterface $loader;

    public function __construct()
    {
        $this->fileReader = new FileReader();
        $this->parser = new Parser();
        $this->loader = new Loader();
    }
    /**
     * @inheritDoc
     */
    public function load(string $filePath): void
    {
        $fileContent = $this->fileReader->getContentAsArray($filePath);

        if (empty($fileContent)) {
            return;
        }

        $data = $this->parser->getParsedData($fileContent);

        if (empty($data)) {
            return;
        }

        $this->loader->load($data);
    }

    /**
     * @inheritDoc
     */
    public function overload(string $filePath): void
    {
        $fileContent = $this->fileReader->getContentAsArray($filePath);

        if (empty($fileContent)) {
            return;
        }

        $data = $this->parser->getParsedData($fileContent);

        if (empty($data)) {
            return;
        }

        $this->loader->overLoad($data);
    }
}
