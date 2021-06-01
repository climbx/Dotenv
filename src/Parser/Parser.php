<?php

namespace Climbx\Dotenv\Parser;

class Parser implements ParserInterface
{
    private LineParser $lineParser;
    private KeyParser $keyParser;
    private ValueParser $valueParser;

    public function __construct()
    {
        $this->lineParser = new LineParser();
        $this->keyParser = new KeyParser();
        $this->valueParser = new ValueParser();
    }

    /**
     * @param array $fileContent
     *
     * @return array
     * @throws DotenvParserException
     */
    public function getParsedData(array $fileContent): array
    {
        $data = [];

        foreach ($fileContent as $lineIndex => $lineContent) {
            if ($this->lineParser->isDataLine($lineContent)) {
                $line = $this->lineParser->getLineRawData($lineIndex, $lineContent);
                $key = $this->keyParser->getKey($line);
                $value = $this->valueParser->getValue($line, $data);

                $data[$key] = $value;
            }
        }

        return $data;
    }
}
