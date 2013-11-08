<?php

class Wrapper
{
    public static function wrap($paragraph, $columnNumber)
    {
        if (strlen($paragraph) > $columnNumber) {
            return implode(
                "\n", 
                self::breakInLines($paragraph, $columnNumber)
            );
        }
        return $paragraph;
    }

    private static function breakInLines($paragraph, $columnNumber)
    {
        if (strlen($paragraph) > 2 * $columnNumber) {
            return [
                substr($paragraph, 0, $columnNumber),
                substr($paragraph, $columnNumber, $columnNumber),
                substr($paragraph, $columnNumber * 2)
            ];
        } else {
            return [
                substr($paragraph, 0, $columnNumber),
                substr($paragraph, $columnNumber)
            ];
        }
    }
}

class WrapperTest extends \PHPUnit_Framework_TestCase
{
    public function testAShortLineDoesNotNeedWrapping()
    {
        $this->assertEquals(
            "Hello World",
            Wrapper::wrap("Hello World", 20)
        );
    }

    public function testAParagraphOfTwoPerfectlyMatchingWordsIsWrappedInTwoLines()
    {
        $this->assertMultipleLines(
            "Hello ",
            "World",
            Wrapper::wrap("Hello World", 6)
        );
    }

    public function testAParagraphOfThreePerfectlyMatchingWordsIsWrappedInThreeLines()
    {
        //$this->markTestIncomplete();
        $this->assertMultipleLines(
            "Hello ",
            "World ",
            "Cat",
            Wrapper::wrap("Hello World Cat", 6)
        );
    }

    private function assertMultipleLines(/*$line, $line, ..., $arrayOfLines*/)
    {
        $arguments = func_get_args();
        $arrayOfLines = array_pop($arguments);
        $this->assertEquals(
            implode("\n", $arguments),
            $arrayOfLines
        );
    }
}
