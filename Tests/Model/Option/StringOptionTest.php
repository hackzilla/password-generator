<?php

namespace Hackzilla\PasswordGenerator\Tests\Model\Option;

use Hackzilla\PasswordGenerator\Model\Option\StringOption;

class StringOptionTest extends \PHPUnit\Framework\TestCase
{
    public function testInstantiation()
    {
        $option = new StringOption();

        $this->assertInstanceOf('Hackzilla\PasswordGenerator\Model\Option\OptionInterface', $option);
        $this->assertInstanceOf('Hackzilla\PasswordGenerator\Model\Option\StringOption', $option);
    }

    public function testType()
    {
        $option = new StringOption();

        $this->assertSame(StringOption::TYPE_STRING, $option->getType());
    }

    /**
     * @dataProvider validValueProvider
     *
     * @param mixed $value
     */
    public function testValidValue($value)
    {
        $option = new StringOption();
        $option->setValue($value);

        $this->assertSame($option->getValue(), $value);
    }

    public function validValueProvider()
    {
        return array(
            array('a'),
        );
    }

    /**
     * @dataProvider invalidValueProvider
     *
     * @param mixed $value
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidValue($value)
    {
        $option = new StringOption();
        $option->setValue($value);
    }

    public function invalidValueProvider()
    {
        return array(
            array(true),
            array(false),
            array(1),
            array(null),
            array(1.1),
        );
    }

    /**
     * @dataProvider minMaxProvider
     *
     * @param $min
     * @param $max
     * @param $value
     */
    public function testMinMax($min, $max, $value)
    {
    }

    public function minMaxProvider()
    {
        return array(
            array(0, 255, 'abcdefghijklmnopqrstuvwxyz'),
            array(0, 1, 'a'),
            array(1, 1, 'a'),
            array(4, 4, 'abcd'),
            array(0, 10, 'abcdef'),
        );
    }

    /**
     * @dataProvider minMaxExceptionProvider
     * @expectedException \InvalidArgumentException
     *
     * @param $min
     * @param $max
     * @param $value
     */
    public function testMinMaxException($min, $max, $value)
    {
        $option = new StringOption(array('min' => $min, 'max' => $max));
        $option->setValue($value);
    }

    public function minMaxExceptionProvider()
    {
        return array(
            array(0, 5, 'abcdef'),
            array(0, 1, 'ab'),
            array(1, 15, ''),
        );
    }
}
