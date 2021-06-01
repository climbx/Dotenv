<?php

namespace Climbx\Dotenv\Parser;

trait ValueStepMiddleParserTrait
{
    /**
     * @param ValueParserState $state
     *
     * @return bool
     */
    private function isEndValueChar(ValueParserState $state): bool
    {
        if ($state->getCurrentChar()->isSingleQuote() && $state->isSingleQuoted()) {
            return true;
        }

        if ($state->getCurrentChar()->isDoubleQuote() && $state->isDoubleQuoted()) {
            return true;
        }

        if ($state->getCurrentChar()->isWhiteSpace() && $state->isNotQuoted()) {
            return true;
        }

        return false;
    }
}
