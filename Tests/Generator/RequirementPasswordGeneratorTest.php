<?php

namespace Hackzilla\PasswordGenerator\Tests\Generator;

use Hackzilla\PasswordGenerator\Generator\RequirementPasswordGenerator;

class RequirementPasswordGeneratorTest extends \PHPUnit_Framework_TestCase
{
    private $_object;

    /**
     *
     */
    public function setup()
    {
        $this->_object = new RequirementPasswordGenerator();

        $this->_object
            ->setOptionValue(RequirementPasswordGenerator::OPTION_UPPER_CASE, false)
            ->setOptionValue(RequirementPasswordGenerator::OPTION_LOWER_CASE, false)
            ->setOptionValue(RequirementPasswordGenerator::OPTION_NUMBERS, false)
            ->setOptionValue(RequirementPasswordGenerator::OPTION_SYMBOLS, false)
            ->setOptionValue(RequirementPasswordGenerator::OPTION_AVOID_SIMILAR, false);
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
     * @dataProvider lengthProvider
     *
     * @param $length
     */
    public function testGeneratePassword($length)
    {
        $this->_object
            ->setOptionValue(RequirementPasswordGenerator::OPTION_UPPER_CASE, true)
            ->setOptionValue(RequirementPasswordGenerator::OPTION_LOWER_CASE, true)
            ->setOptionValue(RequirementPasswordGenerator::OPTION_NUMBERS, true)
            ->setOptionValue(RequirementPasswordGenerator::OPTION_SYMBOLS, true)
            ->setOptionValue(RequirementPasswordGenerator::OPTION_AVOID_SIMILAR, true);

        $this->_object->setLength($length);
        $this->assertSame(\strlen($this->_object->generatePassword()), $length);
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

    /**
     * @dataProvider getterSetterProvider
     *
     * @param $method
     */
    public function testGetterSetters($method)
    {
        $this->_object->{'set'.$method}(true);
        $this->assertTrue($this->_object->{'get'.$method}());

        $this->_object->{'set'.$method}(false);
        $this->assertTrue(!$this->_object->{'get'.$method}());
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

    /**
     * @dataProvider      getterSetterProvider
     * @expectedException \InvalidArgumentException
     *
     * @param $method
     */
    public function testGetterSettersException($method)
    {
        $this->_object->{'set'.$method}(1);
    }

    public function getterSetterProvider()
    {
        return array(
            array('Uppercase'),
            array('Lowercase'),
            array('Numbers'),
            array('Symbols'),
            array('AvoidSimilar'),
        );
    }

    /**
     * @dataProvider optionProvider
     *
     * @param $option
     * @param $exists
     * @param $dontExist
     */
    public function testSetOption($option, $exists, $dontExist)
    {
        $this->_object->setOptionValue($option, true);
        $availableCharacters = $this->_object->getCharacterList()->getCharacters();

        foreach ($exists as $check) {
            $this->assertContains($check, $availableCharacters);
        }
        foreach ($dontExist as $check) {
            $this->assertNotContains($check, $availableCharacters);
        }
    }

    /**
     * @return array
     */
    public function optionProvider()
    {
        return array(
            array(RequirementPasswordGenerator::OPTION_UPPER_CASE, array('A', 'B', 'C'), array('a', 'b', 'c')),
            array(RequirementPasswordGenerator::OPTION_LOWER_CASE, array('a', 'b', 'c'), array('A', 'B', 'C')),
            array(RequirementPasswordGenerator::OPTION_NUMBERS, array('1', '2', '3'), array('a', 'b', 'c', 'A', 'B', 'C')),
            array(RequirementPasswordGenerator::OPTION_SYMBOLS, array('+', '=', '?'), array('a', 'b', 'c', 'A', 'B', 'C')),
        );
    }

    public function testSetOptionSimilar()
    {
        $exists = array('a', 'b', 'c', 'A', 'B', 'C');
        $dontExist = array('o', 'l', 'O');

        $this->_object
            ->setOptionValue(RequirementPasswordGenerator::OPTION_UPPER_CASE, true)
            ->setOptionValue(RequirementPasswordGenerator::OPTION_LOWER_CASE, true)
            ->setOptionValue(RequirementPasswordGenerator::OPTION_AVOID_SIMILAR, true);

        $availableCharacters = $this->_object->getCharacterList()->getCharacters();

        foreach ($exists as $check) {
            $this->assertContains($check, $availableCharacters);
        }
        foreach ($dontExist as $check) {
            $this->assertNotContains($check, $availableCharacters);
        }
    }

    /**
     * @dataProvider optionsProvider
     *
     * @param $option
     * @param $parameter
     */
    public function testSetGet($option, $parameter)
    {
        $this->_object
            ->setOptionValue($option, true)
            ->setParameter($parameter, 'A')
            ->setLength(4);

        $this->assertSame('AAAA', $this->_object->generatePassword());
    }

    /**
     * @return array
     */
    public function optionsProvider()
    {
        return array(
            array(RequirementPasswordGenerator::OPTION_UPPER_CASE, RequirementPasswordGenerator::PARAMETER_UPPER_CASE),
            array(RequirementPasswordGenerator::OPTION_LOWER_CASE, RequirementPasswordGenerator::PARAMETER_LOWER_CASE),
            array(RequirementPasswordGenerator::OPTION_NUMBERS, RequirementPasswordGenerator::PARAMETER_NUMBERS),
            array(RequirementPasswordGenerator::OPTION_SYMBOLS, RequirementPasswordGenerator::PARAMETER_SYMBOLS),
        );
    }

    /**
     *
     */
    public function testAvoidSimilar()
    {
        $this->_object
            ->setParameter(RequirementPasswordGenerator::PARAMETER_UPPER_CASE, 'AB')
            ->setParameter(RequirementPasswordGenerator::PARAMETER_SIMILAR, 'B')
            ->setOptionValue(RequirementPasswordGenerator::OPTION_UPPER_CASE, true)
            ->setOptionValue(RequirementPasswordGenerator::OPTION_AVOID_SIMILAR, true);

        $this->_object->setLength(4);

        $this->assertSame('AAAA', $this->_object->generatePassword());
    }

    /**
     *
     */
    public function testCharacterListException()
    {
        $this->setExpectedException('\Hackzilla\PasswordGenerator\Exception\CharactersNotFoundException');
        $this->_object->getCharacterList();
    }

    /**
     * @dataProvider validOptionProvider
     */
    public function testValidOption($option, $valid)
    {
        $this->assertSame($valid, $this->_object->validOption($option));
    }

    public function validOptionProvider()
    {
        return array(
            array(RequirementPasswordGenerator::OPTION_UPPER_CASE, true),
            array(RequirementPasswordGenerator::OPTION_LOWER_CASE, true),
            array(RequirementPasswordGenerator::OPTION_NUMBERS, true),
            array(RequirementPasswordGenerator::OPTION_SYMBOLS, true),
            array(null, false),
            array('', false),
            array(RequirementPasswordGenerator::OPTION_AVOID_SIMILAR, false),
            array(RequirementPasswordGenerator::OPTION_LENGTH, false),
        );
    }

    /**
     * @dataProvider minMaxProvider
     *
     * @param $option
     * @param $count
     */
    public function testMinMax($option, $count)
    {
        $this->_object->setMinimumCount($option, $count);
        $this->assertSame($count, $this->_object->getMinimumCount($option));

        $this->_object->setMaximumCount($option, $count);
        $this->assertSame($count, $this->_object->getMaximumCount($option));
    }

    public function minMaxProvider()
    {
        return array(
            array(RequirementPasswordGenerator::OPTION_UPPER_CASE, null),
            array(RequirementPasswordGenerator::OPTION_UPPER_CASE, 1),
            array(RequirementPasswordGenerator::OPTION_UPPER_CASE, 2),
            array(RequirementPasswordGenerator::OPTION_LOWER_CASE, null),
            array(RequirementPasswordGenerator::OPTION_LOWER_CASE, 1),
            array(RequirementPasswordGenerator::OPTION_LOWER_CASE, 2),
            array(RequirementPasswordGenerator::OPTION_NUMBERS, null),
            array(RequirementPasswordGenerator::OPTION_NUMBERS, 1),
            array(RequirementPasswordGenerator::OPTION_NUMBERS, 2),
            array(RequirementPasswordGenerator::OPTION_SYMBOLS, null),
            array(RequirementPasswordGenerator::OPTION_SYMBOLS, 1),
            array(RequirementPasswordGenerator::OPTION_SYMBOLS, 2),
        );
    }

    public function testValidateLimits()
    {
        $this->_object
            ->setLength(4)
            ->setMinimumCount(RequirementPasswordGenerator::OPTION_UPPER_CASE, 1)
            ->setMinimumCount(RequirementPasswordGenerator::OPTION_LOWER_CASE, 1)
            ->setMinimumCount(RequirementPasswordGenerator::OPTION_NUMBERS, 1)
            ->setMinimumCount(RequirementPasswordGenerator::OPTION_SYMBOLS, 1)
        ;

        $this->assertTrue($this->_object->validateLimits());
    }

    public function testValidateLimitsFalse()
    {
        $this->_object
            ->setLength(3)
            ->setMinimumCount(RequirementPasswordGenerator::OPTION_UPPER_CASE, 1)
            ->setMinimumCount(RequirementPasswordGenerator::OPTION_LOWER_CASE, 1)
            ->setMinimumCount(RequirementPasswordGenerator::OPTION_NUMBERS, 1)
            ->setMinimumCount(RequirementPasswordGenerator::OPTION_SYMBOLS, 1)
        ;

        $this->assertFalse($this->_object->validateLimits());
    }

    /**
     * @dataProvider validatePasswordProvider
     *
     * @param $password
     * @param $option
     * @param $min
     * @param $max
     * @param $valid
     */
    public function testValidatePassword($password, $option, $min, $max, $valid)
    {
        $this->_object->setMinimumCount($option, $min);
        $this->_object->setMaximumCount($option, $max);

        $this->assertSame($valid, $this->_object->validatePassword($password));
    }

    public function validatePasswordProvider()
    {
        return array(
            array('ABCdef', RequirementPasswordGenerator::OPTION_UPPER_CASE, 2, 3, true),
            array('ABcdef', RequirementPasswordGenerator::OPTION_UPPER_CASE, 2, 3, true),
            array('Abcdef', RequirementPasswordGenerator::OPTION_UPPER_CASE, 2, 3, false),
            array('ABCdef^\'%', RequirementPasswordGenerator::OPTION_SYMBOLS, 2, 3, true),
            array('ABcdef!@', RequirementPasswordGenerator::OPTION_SYMBOLS, 2, 3, true),
            array('Abcdef!', RequirementPasswordGenerator::OPTION_SYMBOLS, 2, 3, false),
        );
    }
}