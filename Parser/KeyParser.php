<?php

namespace Climbx\Dotenv\Parser;

use Climbx\Dotenv\Exception\ParserException;

class KeyParser
{
    private const VALID_NAME_REGEX = '^[a-zA-Z]+(_?[a-zA-Z0-9]+)*$';

    /**
     * @param Line $line
     *
     * @return string
     * @throws ParserException
     */
    public function getKey(Line $line): string
    {
        if ($this->hasWhitespaceAtTheEnd($line->getKeyData())) {
            throw new ParserException(sprintf(
                'Whitespace before assignment operator are not allowed. Line %s',
                $line->getNumber()
            ));
        }

        if ($this->isNotValid($line->getKeyData())) {
            throw new ParserException(sprintf(
                'Illegal key name. Line %s',
                $line->getNumber()
            ));
        }

        return $line->getKeyData();
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    private function hasWhitespaceAtTheEnd(string $key): bool
    {
        return substr($key, -1) === ' ';
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    private function isNotValid(string $key): bool
    {
        return preg_match('#' . self::VALID_NAME_REGEX . '#', $key) === 0;
    }
}
