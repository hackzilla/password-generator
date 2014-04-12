<?php

namespace Hackzilla\PasswordGenerator\Tests\Generator;

use Hackzilla\PasswordGenerator\Generator\PasswordGenerator;

class PasswordGeneratorTest extends \PHPUnit_Framework_TestCase
{

    private $_object;

    public function setup()
    {
        $this->_object = new PasswordGenerator();
    }

    /**
     * @dataProvider lengthProvider
     */
    public function testGeneratePasswords($length)
    {
        $this->_object->setLength($length);
        $passwords = $this->_object->generatePasswords($length);

        $this->assertEquals(count($passwords), $length);

        foreach ($passwords as $password) {
            $this->assertEquals(\strlen($password), $length);
        }
    }

    /**
     * @dataProvider lengthProvider
     */
    public function testGeneratePassword($length)
    {
        $this->_object->setLength($length);
        $this->assertEquals(\strlen($this->_object->generatePassword()), $length);
    }

    public function lengthProvider()
    {
        return array(
            array(1),
            array(4),
            array(8),
            array(16),
        );
    }

    /**
     * @dataProvider optionProvider
     */
    public function testSetOption($options, $exists, $dontExist)
    {
        $this->_object->setOptions($options);
        $availableCharacters = $this->_object->getCharacterList();

        foreach ($exists as $check) {
            $this->assertContains($check, $availableCharacters);
        }
        foreach ($dontExist as $check) {
            $this->assertNotContains($check, $availableCharacters);
        }
    }

    public function optionProvider()
    {
        return array(
            array(PasswordGenerator::OPTION_UPPER_CASE, array('A', 'B', 'C'), array('a', 'b', 'c')),
            array(PasswordGenerator::OPTION_LOWER_CASE, array('a', 'b', 'c'), array('A', 'B', 'C')),
            array(PasswordGenerator::OPTION_NUMBERS, array('1', '2', '3'), array('a', 'b', 'c', 'A', 'B', 'C')),
            array(PasswordGenerator::OPTION_SYMBOLS, array('+', '=', '?'), array('a', 'b', 'c', 'A', 'B', 'C')),
            array(PasswordGenerator::OPTION_LOWER_CASE | PasswordGenerator::OPTION_UPPER_CASE | PasswordGenerator::OPTION_AVOID_SIMILAR, array('a', 'b', 'c', 'A', 'B', 'C'), array('o', 'l', 'O')),
        );
    }

}
