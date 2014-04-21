<?php

namespace Hackzilla\PasswordGenerator\Tests\Generator;

use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

class ComputerPasswordGeneratorTest extends \PHPUnit_Framework_TestCase
{

    private $_object;

    public function setup()
    {
        $this->_object = new ComputerPasswordGenerator();
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
            array(ComputerPasswordGenerator::OPTION_UPPER_CASE, array('A', 'B', 'C'), array('a', 'b', 'c')),
            array(ComputerPasswordGenerator::OPTION_LOWER_CASE, array('a', 'b', 'c'), array('A', 'B', 'C')),
            array(ComputerPasswordGenerator::OPTION_NUMBERS, array('1', '2', '3'), array('a', 'b', 'c', 'A', 'B', 'C')),
            array(ComputerPasswordGenerator::OPTION_SYMBOLS, array('+', '=', '?'), array('a', 'b', 'c', 'A', 'B', 'C')),
            array(ComputerPasswordGenerator::OPTION_LOWER_CASE | ComputerPasswordGenerator::OPTION_UPPER_CASE | ComputerPasswordGenerator::OPTION_AVOID_SIMILAR, array('a', 'b', 'c', 'A', 'B', 'C'), array('o', 'l', 'O')),
        );
    }

    public function optionsProvider()
    {
        return array(
            ComputerPasswordGenerator::OPTION_UPPER_CASE,
            ComputerPasswordGenerator::OPTION_LOWER_CASE,
            ComputerPasswordGenerator::OPTION_NUMBERS,
            ComputerPasswordGenerator::OPTION_SYMBOLS,
            ComputerPasswordGenerator::OPTION_AVOID_SIMILAR,
        );
    }

}
