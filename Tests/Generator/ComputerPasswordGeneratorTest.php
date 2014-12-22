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

    /**
     * @dataProvider optionsProvider
     */
    public function testSetGet($method, $option)
    {
        $this->_object->{'set' . $method}('A');
        $this->_object->setOptions($option);
        $this->_object->setLength(4);

        $this->assertEquals('AAAA', $this->_object->generatePassword());
    }

    public function optionsProvider()
    {
        return array(
            array('UppercaseLetters', ComputerPasswordGenerator::OPTION_UPPER_CASE),
            array('LowercaseLetters', ComputerPasswordGenerator::OPTION_LOWER_CASE),
            array('Numbers', ComputerPasswordGenerator::OPTION_NUMBERS),
            array('Symbols', ComputerPasswordGenerator::OPTION_SYMBOLS),
        );
    }

    public function testAvoidSimilar()
    {
        $this->_object->setUppercaseLetters('AB');
        $this->_object->setAvoidSimilar('B');
        $this->_object->setOptions(ComputerPasswordGenerator::OPTION_UPPER_CASE | ComputerPasswordGenerator::OPTION_AVOID_SIMILAR);
        $this->_object->setLength(4);

        $this->assertEquals('AAAA', $this->_object->generatePassword());
    }

    public function testCharacterListException()
    {
        $this->_object->setOptions(0);
        $this->setExpectedException('\Hackzilla\PasswordGenerator\Exception\CharactersNotFoundException');
        $this->_object->getCharacterList();
    }
}
