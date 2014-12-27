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
     * @param $length
     * @param $comparePassword
     */
    public function testGeneratePasswords($length, $comparePassword)
    {
        $this->_object->setLength($length);
        $passwords = $this->_object->generatePasswords($length);

        $this->assertEquals(count($passwords), $length);

        foreach ($passwords as $password) {
            $this->assertEquals($password, $comparePassword);
        }
    }

    /**
     * @dataProvider lengthProvider
     * @param $length
     * @param $password
     */
    public function testGeneratePassword($length, $password)
    {
        $this->_object->setLength($length);
        $this->assertEquals($this->_object->generatePassword(), $password);
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
}
