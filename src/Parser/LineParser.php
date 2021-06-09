<?php

namespace Climbx\Dotenv\Parser;

use Climbx\Dotenv\Exception\ParserException;

class LineParser
{
    /**
     * @param string $lineContent
     *
     * @return bool
     */
    public function isDataLine(string $lineContent): bool
    {
        $content = trim($lineContent);

        return $this->isNotEmptyLine($content) && $this->isNotCommentLine($content);
    }

    /**
     * @param string $lineContent
     *
     * @return bool
     */
    private function isNotCommentLine(string $lineContent): bool
    {
        return substr($lineContent, 0, 1) !== '#';
    }

    /**
     * @param string $lineContent
     *
     * @return bool
     */
    private function isNotEmptyLine(string $lineContent): bool
    {
        return $lineContent !== '';
    }

    /**
     * @param int    $lineIndex
     * @param string $lineContent
     *
     * @return Line
     * @throws ParserException
     */
    public function getLineRawData(int $lineIndex, string $lineContent): Line
    {
        $lineNumber = $lineIndex + 1;
        $content = trim($lineContent);

        $index = $this->getAssignmentOperatorIndex($content, $lineNumber);
        $keyData = $this->getKeyDataFromContent($content, $index, $lineNumber);
        $valueData = $this->getValueDataFromContent($content, $index);

        return new Line($lineNumber, $keyData, $valueData);
    }

    /**
     * @param int    $lineNumber
     * @param string $lineContent
     *
     * @return int
     * @throws ParserException
     */
    private function getAssignmentOperatorIndex(string $lineContent, int $lineNumber): int
    {
        $index = strpos($lineContent, '=');

        if (false === $index) {
            throw new ParserException(sprintf(
                'Assignment operator is missing. Line %s',
                $lineNumber
            ));
        }

        return $index;
    }

    /**
     * @param string $lineContent
     * @param int    $index
     * @param int    $lineNumber
     *
     * @return string
     * @throws ParserException
     */
    private function getKeyDataFromContent(string $lineContent, int $index, int $lineNumber): string
    {
        if ($index === 0) {
            throw new ParserException(sprintf(
                'Key is missing. Line %s',
                $lineNumber
            ));
        }

        return substr($lineContent, 0, $index);
    }

    /**
     * @param string $lineContent
     * @param int    $index
     *
     * @return string
     */
    private function getValueDataFromContent(string $lineContent, int $index): string
    {
        $lineLength = strlen($lineContent);
        $valueStartIndex = $index + 1;
        $valueLength = $lineLength - $valueStartIndex;

        if ($valueLength === 0) {
            return '';
        }

        return substr($lineContent, -($valueLength));
    }
}
