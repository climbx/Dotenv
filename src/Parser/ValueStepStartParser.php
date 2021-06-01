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
        if ($currentChar->isWhiteSpace()) {
            throw new DotenvParserException(sprintf(
                'Whitespace after assignment operator are not allowed. Line %s',
                $state->getLineNumber()
            ));
        }

        if ($currentChar->isQuote() && $state->isLastLineChar()) {
            throw new DotenvParserException(sprintf(
                'Closing quote is missing for value. Line %s',
                $state->getLineNumber()
            ));
        }

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
}
