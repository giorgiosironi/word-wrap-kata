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
        if (self::nextChar($paragraph, $columnNumber) != ' ') {
            $hypotheticalFirstLine = self::cutColumns($paragraph, $columnNumber);
            return self::lastSpace($hypotheticalFirstLine);
        }
        return $columnNumber;
    }

    private static function cutColumns($string, $length)
    {
        return substr($string, 0, $length);
    }

    private static function nextChar($string, $length)
    {
        return substr($string, $length, 1);
    }

    private static function lastSpace($string)
    {
        $lengthOfFirstLine = strlen($string);
        $firstSpacePosition = strrpos($string, ' ');
        if ($firstSpacePosition !== false) {
            $lengthOfFirstLine = $firstSpacePosition + 1;
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

        $this->assertMultipleLines(
            "Super",
            "calif",
            "ragi",
            Wrapper::wrap("Supercalifragi", 5)
        );
    }

    public function testExploratory()
    {
        echo PHP_EOL, Wrapper::wrap(" You write a class called Wrapper, that has a single static function named wrap that takes two arguments, a string, and a column number. The function returns the string, but with line breaks inserted at just the right places to make sure that no line is longer than the column number. You try to break lines at word boundaries. ", 20);
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
