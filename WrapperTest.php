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
        if (strlen($paragraph) <= $columnNumber) {
            return [$paragraph];
        }
        $lengthOfFirstLine = self::lengthOfFirstLine($paragraph, $columnNumber);
        return array_merge(
            [
                substr($paragraph, 0, $lengthOfFirstLine),
            ],
            self::breakInLines(
                substr($paragraph, $lengthOfFirstLine),
                $columnNumber
            )
        );
    }

    private static function lengthOfFirstLine($paragraph, $columnNumber)
    {
        $lengthOfFirstLine = $columnNumber;
        $hypotheticalFirstLine = substr($paragraph, 0, $lengthOfFirstLine);
        if (substr($paragraph, $lengthOfFirstLine, 1) != ' ') {
            $firstSpacePosition = strrpos($hypotheticalFirstLine, ' ');
            if ($firstSpacePosition !== false) {
                $lengthOfFirstLine = $firstSpacePosition + 1;
            }
        }
        return $lengthOfFirstLine;
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
        $this->assertMultipleLines(
            "Hi ",
            "Kitty ",
            "Cat",
            Wrapper::wrap("Hi Kitty Cat", 6)
        );
    }

    public function testMultipleShortWordsAreNotAProblem()
    {
        $this->assertMultipleLines(
            "Hi my ",
            "Kitty ",
            "Cat !",
            Wrapper::wrap("Hi my Kitty Cat !", 6)
        );
    }

    public function testSpacesCanGoOnTheNextLineToFitThePreviousWord()
    {
        $this->assertMultipleLines(
            "Hi my",
            " Cat",
            Wrapper::wrap("Hi my Cat", 5)
        );
    }

    public function testAWordLongAsTheColumnNumberCanBeFitOnALine()
    {
        $this->assertMultipleLines(
            "Hello",
            " Cat",
            Wrapper::wrap("Hello Cat", 5)
        );
    }

    public function testAWordLongerThanTheColumnNumberMustBeBroken()
    {
        $this->assertMultipleLines(
            "Hel",
            "lo ",
            "Cat",
            Wrapper::wrap("Hello Cat", 3)
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
