<?php

namespace Climbx\Dotenv;

use Climbx\Filesystem\FileHelper;
use Climbx\Dotenv\Loader\Loader;
use Climbx\Dotenv\Loader\LoaderInterface;
use Climbx\Dotenv\Parser\Parser;
use Climbx\Dotenv\Parser\ParserInterface;

class Dotenv implements DotenvInterface
{
    private FileHelper $fileHelper;
    private ParserInterface $parser;
    private LoaderInterface $loader;

    public function __construct()
    {
        $this->fileHelper = new FileHelper();
        $this->parser = new Parser();
        $this->loader = new Loader();
    }
    /**
     * @inheritDoc
     */
    public function load(string $filePath): void
    {
        $fileContent = $this->fileHelper->getContentAsArray($filePath);

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
        $fileContent = $this->fileHelper->getContentAsArray($filePath);

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
