<?php

namespace Climbx\Dotenv\Parser;

use Climbx\Dotenv\Exception\ParserException;

/*
 * This class parses all the characters from the second up to the end-of-value one.
 */
class ValueStepMiddleParser implements ValueStepParserInterface
{
    use ValueStepMiddleParserTrait;

    private ValueStepMiddleReferenceParser $referenceParser;

    public function __construct()
    {
        $this->referenceParser = new ValueStepMiddleReferenceParser();
    }

    /**
     * @param ValueParserState $state
     *
     * @throws ParserException
     */
    public function parseChar(ValueParserState $state): void
    {
        $currentChar = $state->getCurrentChar();

        // Steps order is important :

        // Step 1
        if ($currentChar->isQuote() && $state->isNotQuoted()) {
            throw new ParserException(sprintf(
                'Opening quote is missing for value. Line %s',
                $state->getLineNumber()
            ));
        }

        // Step 2
        if ($state->isInReference()) {
            $this->referenceParser->parseChar($state);
            return;
        }

        // Step 3
        if ($currentChar->isReferenceDeclaration()) {
            $this->parseReferenceDeclarationChar($state);
            return;
        }

        // Step 4
        if ($this->isEndValueChar($state)) {
            $state->setStepToEnd();
            return;
        }

        // Step 5
        if ($state->isLastLineChar()) {
            $this->parseLastLineChar($state);
            return;
        }

        // Step 6
        $state->addCurrentCharToValue();
    }

    /**
     * @param ValueParserState $state
     */
    private function parseReferenceDeclarationChar(ValueParserState $state): void
    {
        if ($state->isEscapedChar()) {
            $state->removeLastValueChar();
            $state->addCurrentCharToValue();
            return;
        }

        $state->setToReference();
    }

    /**
     * @param ValueParserState $state
     *
     * @throws ParserException
     */
    private function parseLastLineChar(ValueParserState $state)
    {
        $state->addCurrentCharToValue();

        if ($state->isQuoted() && !$state->getCurrentChar()->isQuote()) {
            throw new ParserException(sprintf(
                'Closing quote is missing for value. Line %s',
                $state->getLineNumber()
            ));
        }
    }
}
