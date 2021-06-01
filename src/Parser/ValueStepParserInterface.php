<?php

namespace Climbx\Dotenv\Parser;

interface ValueStepParserInterface
{
    public function parseChar(ValueParserState $state): void;
}
