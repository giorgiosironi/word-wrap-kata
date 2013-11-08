<?php

class Wrapper
{
    public static function wrap($paragraph, $columnNumber)
    {
        return implode(
            "\n", 
            self::breakInLines($paragraph, $columnNumber)
        );
    }

    private static function breakInLines($paragraph, $columnNumber)
    {
        if (strlen($paragraph) > $columnNumber) {
            $lengthOfFirstLine = $columnNumber;
            return array_merge(
                [
                    substr($paragraph, 0, $lengthOfFirstLine),
                ],
                self::breakInLines(
                    substr($paragraph, $lengthOfFirstLine),
                    $columnNumber
                )
            );
        } else {
            return [$paragraph];
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
        $this->assertMultipleLines(
            "Hello ",
            "World ",
            "Cat",
            Wrapper::wrap("Hello World Cat", 6)
        );
    }

    public function testWordsThatWouldBeCutAreSentOnTheNextLine()
    {
        $this->markTestIncomplete();
        $this->assertMultipleLines(
            "Hi ",
            "Kitty ",
            "Cat",
            Wrapper::wrap("Hi Kitty Cat", 6)
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
