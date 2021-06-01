<?php

namespace Climbx\Dotenv\Parser;

class ValueParserState
{
    public const STEP_START = 'STEP_START';
    public const STEP_MIDDLE = 'STEP_MIDDLE';
    public const STEP_END = 'STEP_END';

    private const QUOTES_NONE = 'QUOTES_NONE';
    private const QUOTES_SINGLE = 'QUOTES_SINGLE';
    private const QUOTES_DOUBLE = 'QUOTES_DOUBLE';

    /**
     * @var int
     */
    private int $lineNumber;

    /**
     * @var int
     */
    private int $charsLength;

    /**
     * @var string
     */
    private string $step;

    /**
     * @var bool
     */
    private bool $toExit;

    /**
     * @var string
     */
    private string $value;

    /**
     * @var array
     */
    private array $data;

    /**
     * @var string
     */
    private string $quotes;

    /**
     * @var Reference
     */
    private Reference $reference;

    /**
     * @var bool
     */
    private bool $inReference;

    /**
     * @var Char
     */
    private Char $currentChar;

    /**
     * @var string
     */
    private string $prevChar;

    /**
     * @var int
     */
    private int $charIndex;

    public function __construct()
    {
        $this->currentChar = new Char();
        $this->reference = new Reference();
    }

    /**
     * @param int   $lineNumber
     * @param int   $charsLength
     * @param array $data
     */
    public function initialize(int $lineNumber, int $charsLength, array $data)
    {
        $this->lineNumber = $lineNumber;
        $this->charsLength = $charsLength;
        $this->data = $data;

        $this->step = self::STEP_START;
        $this->toExit = false;
        $this->value = '';
        $this->reference->initialize();
        $this->inReference = false;
        $this->currentChar->initialize();
        $this->prevChar = '';
        $this->charIndex = 0;
        $this->quotes = self::QUOTES_NONE;
    }

    /**
     * @return int
     */
    public function getLineNumber(): int
    {
        return $this->lineNumber;
    }

    /**
     * @return Char
     */
    public function getCurrentChar(): Char
    {
        return $this->currentChar;
    }

    public function setCurrentChar(string $char): void
    {
        $this->setPrevChar();
        $this->increaseCharIndex();
        $this->currentChar->setChar($char);
    }

    private function setPrevChar(): void
    {
        if ($this->currentChar->getChar() !== '') {
            $this->prevChar = $this->currentChar->getChar();
        }
    }

    private function increaseCharIndex(): void
    {
        $this->charIndex ++;
    }

    public function addCurrentCharToValue(): void
    {
        $this->value .= $this->currentChar->getChar();
    }

    public function addCurrentCharToReference(): void
    {
        $this->reference->addCharToName($this->getCurrentChar()->getChar());
    }

    public function addReferenceToValue(): void
    {
        $this->value .= $this->data[$this->reference->getName()];
    }

    /**
     * @return bool
     */
    public function isLastLineChar(): bool
    {
        return $this->charIndex === $this->charsLength;
    }

    /**
     * @return bool
     */
    public function isEscapedChar(): bool
    {
        return $this->prevChar === '\\';
    }

    /**
     * @return string
     */
    public function getStep(): string
    {
        return $this->step;
    }

    /**
     * @return bool
     */
    public function isToExit(): bool
    {
        return $this->toExit;
    }

    public function setToExit(): void
    {
        $this->toExit = true;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    public function removeLastValueChar(): void
    {
        $this->value = substr($this->value, 0, -1);
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @return Reference
     */
    public function getReference(): Reference
    {
        return $this->reference;
    }

    public function resetReference(): void
    {
        $this->reference->initialize();
    }

    public function resetInReference(): void
    {
        $this->inReference = false;
    }

    /**
     * @return bool
     */
    public function isInReference(): bool
    {
        return $this->inReference;
    }

    public function setToReference(): void
    {
        $this->inReference = true;
    }

    public function setStepToMiddle(): void
    {
        $this->step = self::STEP_MIDDLE;
    }

    public function setStepToEnd(): void
    {
        $this->step = self::STEP_END;
    }

    /**
     * @return bool
     */
    public function isNotQuoted(): bool
    {
        return $this->quotes === self::QUOTES_NONE;
    }

    /**
     * @return bool
     */
    public function isQuoted(): bool
    {
        return !$this->isNotQuoted();
    }

    /**
     * @return bool
     */
    public function isSingleQuoted(): bool
    {
        return $this->quotes === self::QUOTES_SINGLE;
    }

    public function setQuotesToSingle(): void
    {
        $this->quotes = self::QUOTES_SINGLE;
    }

    /**
     * @return bool
     */
    public function isDoubleQuoted(): bool
    {
        return $this->quotes === self::QUOTES_DOUBLE;
    }

    public function setQuotesToDouble(): void
    {
        $this->quotes = self::QUOTES_DOUBLE;
    }
}
