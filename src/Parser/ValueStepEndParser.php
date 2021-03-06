<?php

namespace Climbx\Dotenv\Parser;

use Climbx\Dotenv\Exception\ParserException;

/*
 * This class parses the characters after the end of value declaration
 */
class ValueStepEndParser implements ValueStepParserInterface
{
    /**
     * @param ValueParserState $state
     *
     * @throws ParserException
     */
    public function parseChar(ValueParserState $state): void
    {
        // Steps order is important :

        // Step 1
        if ($state->getCurrentChar()->isWhiteSpace()) {
            return;
        }

        if ($state->getCurrentChar()->isCommentDeclarationChar()) {
            $state->setToExit();
            return;
        }

        /*
         * Next: Every character except whitespace and comment declaration char
         *
         * Step 2
         * New char is found but the value has not been surround by quotes.
         */
        if ($state->isNotQuoted()) {
            throw new ParserException(sprintf(
                'Value with whitespaces must be surrounded by quotes. Line %s',
                $state->getLineNumber()
            ));
        }

        /*
         * Step 3
         * New char is found after closing quote
         */
        throw new ParserException(sprintf(
            'Illegal character outside quotes at the end. Line %s',
            $state->getLineNumber()
        ));
    }
}
