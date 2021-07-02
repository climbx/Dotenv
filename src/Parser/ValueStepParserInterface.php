<?php

namespace Climbx\Dotenv\Parser;

interface ValueStepParserInterface
{
    /**
     * Parses a character in a specific state.
     *
     * @param ValueParserState $state
     */
    public function parseChar(ValueParserState $state): void;
}
