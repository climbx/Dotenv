<?php

namespace Climbx\Dotenv\Parser;

use Climbx\Dotenv\Exception\ParserException;

class ValueStepMiddleReferenceParser implements ValueStepParserInterface
{
    use ValueStepMiddleParserTrait;

    /**
     * @param ValueParserState $state
     *
     * @throws ParserException
     */
    public function parseChar(ValueParserState $state): void
    {
        // Steps order is important

        // Step 1
        if ($state->getCurrentChar()->isOpeningBrace() && $state->getReference()->isFirstChar()) {
            $state->getReference()->setBracesToTrue();
            return;
        }

        // Step 2
        if ($this->isEndReferenceChar($state)) {
            $this->parseEndReferenceChar($state);
            return;
        }

        // Step 3
        if ($this->isEndValueChar($state)) {
            $state->setStepToEnd();
            $this->switchReferenceByValue($state);
            return;
        }

        // Step 4
        if ($state->isLastLineChar()) {
            $this->parseLastLineChar($state);
            return;
        }

        // Step 5
        $state->addCurrentCharToReference();
    }

    /**
     * @param ValueParserState $state
     *
     * @return bool
     */
    private function isEndReferenceChar(ValueParserState $state): bool
    {
        if ($state->getCurrentChar()->isClosingBrace()) {
            return true;
        }

        if ($state->getCurrentChar()->isReferenceDeclaration()) {
            return true;
        }

        if ($state->getCurrentChar()->isWhiteSpace()) {
            return true;
        }

        return false;
    }

    /**
     * @param ValueParserState $state
     *
     * @throws ParserException
     */
    private function parseEndReferenceChar(ValueParserState $state): void
    {
        // Steps order is important

        // Step 1
        if ($state->getCurrentChar()->isClosingBrace() && !$state->getReference()->hasBraces()) {
            throw new ParserException(sprintf(
                'Opening brace is missing in reference declaration. Line %s',
                $state->getLineNumber()
            ));
        }

        // Step 2
        $this->checkClosingBrace($state);

        // Step 3
        $this->switchReferenceByValue($state);

        // Step 4
        if ($state->getCurrentChar()->isWhiteSpace()) {
            $state->addCurrentCharToValue();
        }

        // Step 5
        if (!$state->getCurrentChar()->isReferenceDeclaration()) {
            $state->resetInReference();
        }
    }

    /**
     * @param ValueParserState $state
     */
    private function switchReferenceByValue(ValueParserState $state): void
    {
        if (array_key_exists($state->getReference()->getName(), $state->getData())) {
            $state->addReferenceToValue();
        }

        $state->resetReference();
    }

    /**
     * @param ValueParserState $state
     *
     * @throws ParserException
     */
    private function parseLastLineChar(ValueParserState $state): void
    {
        // Steps order is important

        // Step 1
        $this->checkClosingBrace($state);

        // Step 2
        $state->addCurrentCharToReference();

        // Step 3
        $this->switchReferenceByValue($state);
    }

    /**
     * @param ValueParserState $state
     *
     * @throws ParserException
     */
    private function checkClosingBrace(ValueParserState $state): void
    {
        if ($state->getReference()->hasBraces() && !$state->getCurrentChar()->isClosingBrace()) {
            throw new ParserException(sprintf(
                'Closing brace is missing in reference declaration. Line %s',
                $state->getLineNumber()
            ));
        }
    }
}
