<?php

namespace Hackzilla\PasswordGenerator\Tests\Generator;

use Hackzilla\PasswordGenerator\Generator\HybridPasswordGenerator;

class HybridPasswordGeneratorTest extends \PHPUnit_Framework_TestCase
{

    private $_object;

    public function setup()
    {
        $this->_object = new HybridPasswordGenerator();
    }

    public function passwordProvider()
    {
        return array(
            array(3, 4, '-', '/[A-Za-z0-9]{3}\-[A-Za-z0-9]{3}\-[A-Za-z0-9]{3}\-[A-Za-z0-9]{3}/'),
            array(1, 1, '-', '/[A-Za-z0-9]{1}/'),
            array(1, 2, '', '/[A-Za-z0-9]{1}[A-Za-z0-9]{1}/'),
            array(3, 2, '*', '/[A-Za-z0-9]{3}\*[A-Za-z0-9]{3}/'),
        );
    }

    /**
     * @dataProvider passwordProvider
     */
    public function testGeneratePassword($segmentLength, $segmentCount, $segmentSeparator, $regExp)
    {
        $this->_object->setSegmentLength($segmentLength);
        $this->_object->setSegmentCount($segmentCount);
        $this->_object->setSegmentSeparator($segmentSeparator);

        $this->assertRegExp($regExp, $this->_object->generatePassword());
    }

    /**
     * @dataProvider lengthProvider
     */
    public function testSetSegmentCount($count)
    {
        $this->_object->setSegmentCount($count);
        $this->assertEquals($this->_object->getSegmentCount(), $count);
    }

    /**
     * @dataProvider lengthProvider
     */
    public function testSetSegmentLength($length)
    {
        $this->_object->setSegmentLength($length);
        $this->assertEquals($this->_object->getSegmentLength(), $length);
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
     * @dataProvider segmentProvider
     */
    public function testSetSegmentSeparator($separator)
    {
        $this->_object->setSegmentSeparator($separator);
        $this->assertEquals($this->_object->getSegmentSeparator(), $separator);
    }

    public function segmentProvider()
    {
        return array(
            array(''),
            array('-'),
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
            array(HybridPasswordGenerator::OPTION_UPPER_CASE, array('A', 'B', 'C'), array('a', 'b', 'c')),
            array(HybridPasswordGenerator::OPTION_LOWER_CASE, array('a', 'b', 'c'), array('A', 'B', 'C')),
            array(HybridPasswordGenerator::OPTION_LOWER_CASE | HybridPasswordGenerator::OPTION_UPPER_CASE, array('a', 'b', 'c', 'A', 'B', 'C'), array()),
        );
    }

    /**
     * @dataProvider optionsProvider
     */
    public function testSetGet($method, $option)
    {
        $this->_object->{'set' . $method}('A');
        $this->_object->setOptions($option);
        $this->_object->setSegmentLength(4);
        $this->_object->setSegmentCount(1);

        $this->assertEquals('AAAA', $this->_object->generatePassword());
    }

    public function optionsProvider()
    {
        return array(
            array('UppercaseLetters', HybridPasswordGenerator::OPTION_UPPER_CASE),
            array('LowercaseLetters', HybridPasswordGenerator::OPTION_LOWER_CASE),
            array('Numbers', HybridPasswordGenerator::OPTION_NUMBERS),
        );
    }

    public function testCharacterListException()
    {
        $this->_object->setOptions(0);
        $this->setExpectedException('\Hackzilla\PasswordGenerator\Exception\CharactersNotFoundException');
        $this->_object->getCharacterList();
    }

}
