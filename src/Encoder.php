<?php declare(strict_types=1);

namespace SkillshareShortener;

/**
 * Class Encoder
 *
 * A bijective integer->string->integer encoding class
 *
 * @package SkillshareShortener
 */
class Encoder
{
    private $base;
    private $dictionary;

    /**
     * Encoder constructor.
     *
     * The dictionary is configurable. By default it provides for
     * 1) case-insensitive URLs
     * 2) no ambiguous characters
     * 3) a base-28 namespace
     *
     * @param null|string $dictionary
     */
    public function __construct(?string $dictionary = "23456789bcdfghjkmnpqrstvwxyz")
    {
        $this->dictionary = $dictionary;
        $this->base = strlen($dictionary);
    }

    /**
     * @param int $integer
     * @return string
     */
    public function encode(int $integer): string
    {
        if ($integer === 0) {
            return $this->dictionary[0];
        }

        $string = "";

        while (0 < $integer) {
            $remainder = $integer % $this->base;
            $string .= $this->dictionary[$remainder];
            $integer = ($integer - $remainder) / $this->base;
        }
        return strrev($string);
    }

    /**
     * @param string $string
     * @return int
     */
    public function decode(string $string): int
    {
        if ($string[0] === $this->dictionary[0]) {
            throw new \LogicException("Invalid string");
        }

        $integer = 0;
        foreach (str_split($string) as $char) {
            $pos = strpos($this->dictionary, $char);
            if ($pos === false) {
                throw new \RuntimeException("Invalid character in encoded string");
            }
            $integer = ($integer * $this->base) + $pos;
        }
        return $integer;
    }
}
