<?php

namespace Climbx\Dotenv\Parser;

/*
 * This class represents a line of raw data
 */
class Line
{
    /**
     * @param int    $number
     * @param string $keyData
     * @param string $valueData
     */
    public function __construct(
        private int $number,
        private string $keyData,
        private string $valueData,
    ) {
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getKeyData(): string
    {
        return $this->keyData;
    }

    /**
     * @return string
     */
    public function getValueData(): string
    {
        return $this->valueData;
    }

    /**
     * @return bool
     */
    public function isValueDataEmpty(): bool
    {
        return $this->valueData === '';
    }
}
