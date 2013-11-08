<?php

class Wrapper
{
    public static function wrap($paragraph, $columnNumber)
    {
        return $paragraph;
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
}
