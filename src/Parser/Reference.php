<?php

namespace Climbx\Dotenv\Parser;

/*
 * This class represents a reference to a variable in a value.
 */
class Reference
{
    /**
     * @var string
     */
    private string $name;

    /**
     * @var bool
     */
    private bool $braces;

    public function initialize()
    {
        $this->name = '';
        $this->braces = false;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $char
     */
    public function addCharToName(string $char): void
    {
        $this->name .= $char;
    }

    public function setBracesToTrue(): void
    {
        $this->braces = true;
    }

    /**
     * @return bool
     */
    public function hasBraces(): bool
    {
        return $this->braces;
    }

    /**
     * @return bool
     */
    public function isFirstChar(): bool
    {
        return strlen($this->name) === 0;
    }
}
