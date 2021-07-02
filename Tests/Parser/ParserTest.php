<?php

namespace Climbx\Dotenv\Tests\Parser;

use Climbx\Dotenv\Exception\ParserException;
use Climbx\Dotenv\Parser\Parser;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Climbx\Dotenv\Parser\Parser
 * @covers \Climbx\Dotenv\Parser\LineParser
 * @covers \Climbx\Dotenv\Parser\Line
 * @covers \Climbx\Dotenv\Parser\KeyParser
 * @covers \Climbx\Dotenv\Parser\ValueParser
 * @covers \Climbx\Dotenv\Parser\ValueParserState
 * @covers \Climbx\Dotenv\Parser\ValueStepStartParser
 * @covers \Climbx\Dotenv\Parser\ValueStepMiddleParser
 * @covers \Climbx\Dotenv\Parser\ValueStepMiddleReferenceParser
 * @covers \Climbx\Dotenv\Parser\ValueStepEndParser
 * @covers \Climbx\Dotenv\Parser\Reference
 * @covers \Climbx\Dotenv\Parser\Char
 */
class ParserTest extends TestCase
{
    /**
     * @dataProvider commentLineProvider
     */
    public function testIsCommentLine(array $fileContent)
    {
        $parser = new Parser();

        $parsedData = $parser->getParsedData($fileContent);

        $this->assertEmpty($parsedData);
    }

    public function commentLineProvider(): array
    {
        return [
            [['# This is a comment']],
            [[' # This is a comment']],
            [['#comment']]
        ];
    }

    /**
     * @dataProvider formatErrorProvider
     */
    public function testFormatErrors(array $data, string $message)
    {
        $parser = new Parser();

        $this->expectException(ParserException::class);
        $this->expectExceptionMessage($message);
        $parser->getParsedData($data);
    }

    public function formatErrorProvider(): array
    {
        return [
            # White spaces
            [['FO O=BAR'], 'Illegal key name. Line 1'],
            [['FOO =BAR'], 'Whitespace before assignment operator are not allowed. Line 1'],
            [['FOO= BAR'], 'Whitespace after assignment operator are not allowed. Line 1'],
            [['FOO=BAR BAZ'], 'Value with whitespaces must be surrounded by quotes. Line 1'],

            # Illegal key name
            [['_FOO=BAR'], 'Illegal key name. Line 1'],
            [['FOO_=BAR'], 'Illegal key name. Line 1'],
            [['1FOO=BAR'], 'Illegal key name. Line 1'],
            [['=BAR'], 'Key is missing. Line 1'],

            # Quotes
            [['FOO="BAR'], 'Closing quote is missing for value. Line 1'],
            [['FOO=\'BAR'], 'Closing quote is missing for value. Line 1'],
            [['FOO="'], 'Closing quote is missing for value. Line 1'],
            [['FOO=\''], 'Closing quote is missing for value. Line 1'],
            [['FOO=BAR"'], 'Opening quote is missing for value. Line 1'],
            [['FOO=BAR\''], 'Opening quote is missing for value. Line 1'],
            [['FOO=BAR"BAZ'], 'Opening quote is missing for value. Line 1'],
            [['FOO=BAR\'BAZ'], 'Opening quote is missing for value. Line 1'],
            [['FOO="BAR"BAZ'], 'Illegal character outside quotes at the end. Line 1'],
            [['FOO=\'BAR\' BAZ'], 'Illegal character outside quotes at the end. Line 1'],

            # Comments
            [['FOO=BAR# This is a comment'], 'Value with whitespaces must be surrounded by quotes. Line 1'],

            # Equal Sign
            [['FOO'], 'Assignment operator is missing. Line 1'],
            [['FOO BAR'], 'Assignment operator is missing. Line 1'],

            # References
            [['FOO=$BAR}'], 'Opening brace is missing in reference declaration. Line 1'],
            [['FOO=${BAR'], 'Closing brace is missing in reference declaration. Line 1'],
            [['FOO=${BAR/TEST # comment'], 'Closing brace is missing in reference declaration. Line 1'],
        ];
    }

    /**
     * @dataProvider parserResultProvider
     */
    public function testParserResult(array $data, array $expectedResult)
    {
        $parser = new Parser();

        $parsedData = $parser->getParsedData($data);

        $this->assertEquals($expectedResult, $parsedData);
    }

    public function parserResultProvider(): array
    {
        return [
            # key
            [['FOO=BAR'], ['FOO' => 'BAR']],
            [['MY_FOO=BAR'], ['MY_FOO' => 'BAR']],
            [['FOO_01=BAR'], ['FOO_01' => 'BAR']],
            [['foo=BAR'], ['foo' => 'BAR']],

            # Whitespace
            [[' FOO=BAR'], ['FOO' => 'BAR']],
            [['FOO=BAR '], ['FOO' => 'BAR']],
            [['FOO="BAR "'], ['FOO' => 'BAR ']],
            [['FOO=" BAR BAZ "'], ['FOO' => ' BAR BAZ ']],
            [['FOO=\' BAR \''], ['FOO' => ' BAR ']],

            # quotes
            [['FOO="BAR"'], ['FOO' => 'BAR']],
            [['FOO=\'BAR\''], ['FOO' => 'BAR']],

            # Empty value
            [['FOO=\'\''], ['FOO' => '']],
            [['FOO=""'], ['FOO' => '']],
            [['FOO='], ['FOO' => '']],
            [['FOO= '], ['FOO' => '']],

            # End line chars
            [["FOO=BAR\n"], ['FOO' => 'BAR']],
            [[" FOO=BAR"], ['FOO' => 'BAR']],
            [["FOO=BAR "], ['FOO' => 'BAR']],

            # Backslashes
            [['FOO=BAR\BAZ'], ['FOO' => 'BAR\BAZ']],
            [['FOO="BAR\BAZ"'], ['FOO' => 'BAR\BAZ']],
            [["FOO='BAR\BAZ'"], ['FOO' => 'BAR\BAZ']],
            [["FOO='BAR\BAZ'"], ['FOO' => 'BAR\\BAZ']],
            [["FOO='BAR\\BAZ'"], ['FOO' => 'BAR\\BAZ']],
            [["FOO='BAR\\\BAZ'"], ['FOO' => 'BAR\\\BAZ']],
            [["FOO='BAR\\\BAZ'"], ['FOO' => 'BAR\\\\BAZ']],
            [["FOO='BAR\'"], ['FOO' => 'BAR\\']],

            # Comments
            [["FOO='BAR' # This is a comment"], ['FOO' => 'BAR']],
            [['FOO="BAR" # This is a comment'], ['FOO' => 'BAR']],
            [['FOO=BAR # This is a comment'], ['FOO' => 'BAR']],
            [['FOO=BAR#Comment'], ['FOO' => 'BAR#Comment']],
            [["FOO='BAR'# This is a comment"], ['FOO' => 'BAR']],
            [['FOO="BAR"# This is a comment'], ['FOO' => 'BAR']],

            # References
            [['FOO=BAR', 'BAZ=$FOO'], ['FOO' => 'BAR', 'BAZ' => 'BAR']],
            [['FOO=BAR', 'BAZ=${FOO}/BAR'], ['FOO' => 'BAR', 'BAZ' => 'BAR/BAR']],

            [['FOO=BAR', 'BAZ="$FOO"'], ['FOO' => 'BAR', 'BAZ' => 'BAR']],
            [['FOO=BAR', 'BAZ=\'$FOO\''], ['FOO' => 'BAR', 'BAZ' => 'BAR']],
            [['FOO=BAR', 'BAZ="${FOO}/BAR"'], ['FOO' => 'BAR', 'BAZ' => 'BAR/BAR']],
            [['FOO=BAR', 'BAZ=\'${FOO}/BAR\''], ['FOO' => 'BAR', 'BAZ' => 'BAR/BAR']],

            [['ONE=1', 'TWO=2', 'THREE="$ONE AND $TWO"'], ['ONE' => '1', 'TWO' => '2', 'THREE' => '1 AND 2']],
            [['ONE=1', 'TWO=2', 'THREE="$ONE$TWO"'], ['ONE' => '1', 'TWO' => '2', 'THREE' => '12']],
            [['FIRST=1', 'SECOND=2', 'THIRD=${FIRST}${SECOND}'], ['FIRST' => '1', 'SECOND' => '2', 'THIRD' => '12']],
            [['FIRST=1', 'SECOND=2', 'THIRD=${FIRST}/${SECOND}'], ['FIRST' => '1', 'SECOND' => '2', 'THIRD' => '1/2']],

            [['FOO=\$BAR'], ['FOO' => '$BAR']],
            [['FOO=BAR\$BAZ'], ['FOO' => 'BAR$BAZ']],
            [['FOO=\${BAR}'], ['FOO' => '${BAR}']],

            [['FOO=$'], ['FOO' => '']],
            [['FOO="$ TEST"'], ['FOO' => ' TEST']],
            [['FOO=$_ILLEGAL_KEY'], ['FOO' => '']],
            [['FOO=${_ILLEGAL_KEY}'], ['FOO' => '']],
            [['FOO=BAR', 'BAZ=$EMPTY'], ['FOO' => 'BAR', 'BAZ' => '']],
            [['FOO=BAR', 'BAZ=${EMPTY}END'], ['FOO' => 'BAR', 'BAZ' => 'END']],
        ];
    }
}
