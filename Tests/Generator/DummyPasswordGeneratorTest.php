<?php

namespace Hackzilla\PasswordGenerator\Tests\Generator;

use Hackzilla\PasswordGenerator\Generator\DummyPasswordGenerator;

class DummyPasswordGeneratorTest extends \PHPUnit_Framework_TestCase
{
    private $_object;

    /**
     *
     */
    public function setup()
    {
        $this->_object = new DummyPasswordGenerator();
    }

    /**
     * @dataProvider lengthProvider
     *
     * @param $length
     * @param $comparePassword
     */
    public function testGeneratePasswords($length, $comparePassword)
    {
        $this->_object->setOptionValue(DummyPasswordGenerator::OPTION_LENGTH, $length);
        $passwords = $this->_object->generatePasswords($length);

        $this->assertSame(count($passwords), $length);

        foreach ($passwords as $password) {
            $this->assertSame($password, $comparePassword);
        }
    }

    /**
     * @dataProvider lengthProvider
     *
     * @param $length
     * @param $password
     */
    public function testGeneratePassword($length, $password)
    {
        $this->_object->setOptionValue(DummyPasswordGenerator::OPTION_LENGTH, $length);
        $this->assertSame($this->_object->generatePassword(), $password);
    }

    /**
     * @dataProvider lengthProvider
     *
     * @param $length
     */
    public function testLength($length)
    {
        $this->_object->setLength($length);
        $this->assertSame($this->_object->getLength(), $length);
    }

    /**
     * @return array
     */
    public function lengthProvider()
    {
        return array(
            array(1, 'p'),
            array(4, 'pass'),
            array(8, 'password'),
            array(16, 'password????????'),
        );
    }

    /**
     * @dataProvider      lengthExceptionProvider
     * @expectedException \InvalidArgumentException
     *
     * @param $param
     */
    public function testLengthException($param)
    {
        $this->_object->setLength($param);
    }

    public function lengthExceptionProvider()
    {
        return array(
            array('a'),
            array(false),
            array(null),
            array(-1),
        );
    }
}
