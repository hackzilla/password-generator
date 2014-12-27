<?php

namespace Hackzilla\PasswordGenerator\Tests\Generator;


class AbstractPasswordGeneratorTest extends \PHPUnit_Framework_TestCase
{
    private $_object;

    public function setup()
    {
        $this->_object = $this->getMockBuilder('Hackzilla\PasswordGenerator\Generator\AbstractPasswordGenerator')
            ->disableOriginalConstructor()
            ->setMethods(array('generatePassword'))
            ->getMockForAbstractClass();

        $this->_object->expects($this->any())
            ->method('generatePassword')
            ->will($this->returnValue(''));
    }

    /**
     * @dataProvider generatePasswordsProvider
     * @expectedException \InvalidArgumentException
     * @param $passwordCount
     */
    public function testGeneratePasswordsException($passwordCount)
    {
        $this->_object->generatePasswords($passwordCount);
    }

    public function generatePasswordsProvider()
    {
        return array(
            array(''),
            array(null),
            array(-1),
            array(0.1),
            array(true),
        );
    }

    /**
     * @dataProvider lengthProvider
     * @param $count
     */
    public function testGeneratePasswords($count)
    {
        $passwords = $this->_object->generatePasswords($count);

        $this->assertEquals(\count($passwords), $count);
    }

    /**
     * @return array
     */
    public function lengthProvider()
    {
        return array(
            array(1),
            array(4),
            array(8),
            array(16),
        );
    }
}
