<?php

namespace Climbx\Dotenv\Parser;

class Char
{
    /**
     * @var string
     */
    private string $char;

    /**
     * @return string
     */
    public function getChar(): string
    {
        return $this->char;
    }

    /**
     * @param string $char
     */
    public function setChar(string $char): void
    {
        $this->char = $char;
    }

    public function initialize(): void
    {
        $this->char = '';
    }

    /**
     * @return bool
     */
    public function isWhiteSpace(): bool
    {
        return $this->char === ' ';
    }

    /**
     * @return bool
     */
    public function isQuote(): bool
    {
        return $this->isSingleQuote() || $this->isDoubleQuote();
    }

    /**
     * @return bool
     */
    public function isSingleQuote(): bool
    {
        return $this->char === '\'';
    }

    /**
     * @return bool
     */
    public function isDoubleQuote(): bool
    {
        return $this->char === '"';
    }

    /**
     * @return bool
     */
    public function isOpeningBrace(): bool
    {
        return $this->char === '{';
    }

    /**
     * @return bool
     */
    public function isClosingBrace(): bool
    {
        return $this->char === '}';
    }

    /**
     * @return bool
     */
    public function isReferenceDeclaration(): bool
    {
        return $this->char === '$';
    }

    /**
     * @return bool
     */
    public function isCommentDeclarationChar(): bool
    {
        return $this->char === '#';
    }
}
