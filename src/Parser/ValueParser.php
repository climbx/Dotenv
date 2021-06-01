<?php

namespace Climbx\Dotenv\Parser;

class ValueParser
{
    private ValueParserState $state;
    private ValueStepParserInterface $stepStartParser;
    private ValueStepParserInterface $stepMiddleParser;
    private ValueStepParserInterface $stepEndParser;

    public function __construct()
    {
        $this->state = new ValueParserState();
        $this->stepStartParser = new ValueStepStartParser();
        $this->stepMiddleParser = new ValueStepMiddleParser();
        $this->stepEndParser = new ValueStepEndParser();
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

            $parserName = match ($this->state->getStep()) {
                ValueParserState::STEP_START => 'stepStartParser',
                ValueParserState::STEP_MIDDLE => 'stepMiddleParser',
                ValueParserState::STEP_END => 'stepEndParser',
            };

            $this->$parserName->parseChar($this->state);
        }

        return $this->state->getValue();
    }
}
