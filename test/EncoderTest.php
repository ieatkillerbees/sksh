<?php

require __DIR__ . "/../vendor/autoload.php";

use SkillshareShortener\Encoder;

class EncoderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testDecode(int $integer, string $string)
    {
        $encoder = new Encoder();
        $this->assertEquals($integer, $encoder->decode($string));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testEncode(int $integer, string $string)
    {
        $encoder = new Encoder();
        $this->assertEquals($string, $encoder->encode($integer));
    }

    public function dataProvider(): array
    {
        return [
            [12345, "krx"],
            [999999, "3nkj9"]
        ];
    }
}
