<?php

namespace Climbx\Dotenv\Parser;

/*
 * This class parses the first character of the value.
 */
class ValueStepStartParser implements ValueStepParserInterface
{
    /**
     * @param ValueParserState $state
     *
     * @throws DotenvParserException
     */
    public function parseChar(ValueParserState $state): void
    {
        // Steps order is important

        // Step 1
        $currentChar = $state->getCurrentChar();
        $state->setStepToMiddle();

        // Step 2
        $this->checkWhiteSpace($state);
        $this->checkClosingQuote($state);

        // Step 3
        if ($currentChar->isSingleQuote()) {
            $state->setQuotesToSingle();
            return;
        }

        if ($currentChar->isDoubleQuote()) {
            $state->setQuotesToDouble();
            return;
        }

        if ($currentChar->isReferenceDeclaration()) {
            $state->setToReference();
            return;
        }

        // Step 4
        $state->addCurrentCharToValue();
    }

    /**
     * @param ValueParserState $state
     *
     * @throws DotenvParserException
     */
    private function checkWhiteSpace(ValueParserState $state)
    {
        if ($state->getCurrentChar()->isWhiteSpace()) {
            throw new DotenvParserException(sprintf(
                'Whitespace after assignment operator are not allowed. Line %s',
                $state->getLineNumber()
            ));
        }
    }

    /**
     * @param ValueParserState $state
     *
     * @throws DotenvParserException
     */
    private function checkClosingQuote(ValueParserState $state)
    {
        if ($state->getCurrentChar()->isQuote() && $state->isLastLineChar()) {
            throw new DotenvParserException(sprintf(
                'Closing quote is missing for value. Line %s',
                $state->getLineNumber()
            ));
        }
    }
}
