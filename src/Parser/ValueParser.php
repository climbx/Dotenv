<?php

namespace Climbx\Dotenv\Parser;

class ValueParser
{
    /**
     * @var ValueParserState
     */
    private ValueParserState $state;

    /**
     * @var ValueStepParserInterface[]
     */
    private array $stepParsers;

    public function __construct()
    {
        $this->state = new ValueParserState();
        $this->stepParsers[ValueParserState::STEP_START] = new ValueStepStartParser();
        $this->stepParsers[ValueParserState::STEP_MIDDLE] = new ValueStepMiddleParser();
        $this->stepParsers[ValueParserState::STEP_END] = new ValueStepEndParser();
    }

    /**
     * @param Line  $line
     * @param array $data
     *
     * @return string
     * @throws DotenvParserException
     */
    public function getValue(Line $line, array $data): string
    {
        if ($line->isValueDataEmpty()) {
            return '';
        }

        $charsLength = strlen($line->getValueData());
        $chars = str_split($line->getValueData());
        $this->state->initialize($line->getNumber(), $charsLength, $data);

        foreach ($chars as $char) {
            if ($this->state->isToExit()) {
                break;
            }

            $this->state->setCurrentChar($char);
            $this->stepParsers[$this->state->getStep()]->parseChar($this->state);
        }

        return $this->state->getValue();
    }
}
