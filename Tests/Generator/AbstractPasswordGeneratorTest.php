<?php

namespace Hackzilla\PasswordGenerator\Tests\Generator;


class AbstractPasswordGeneratorTest extends \PHPUnit_Framework_TestCase
{
    private $_object;

    public function setup()
    {
        $this->_object = new AbstractPasswordGeneratorClass();
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

    public function testGetPossibleOptions()
    {
        $this->assertTrue(is_array($this->_object->getOptions()));
        $this->assertEquals(count($this->_object->getOptions()), 5);
    }

    public function testGetOptionValueDefault()
    {
        $this->assertEquals(99, $this->_object->getOptionValue(AbstractPasswordGeneratorClass::OPTION_TEST_INTEGER_DEFAULT));
        $this->assertEquals('test', $this->_object->getOptionValue(AbstractPasswordGeneratorClass::OPTION_TEST_STRING_DEFAULT));
    }

    /**
     * @dataProvider setOptionValueProvider
     * @param $option
     * @param $value
     */
    public function testSetOptionValue($option, $value)
    {
        $this->_object->setOptionValue($option, $value);
        $this->assertEquals($this->_object->getOptionValue($option), $value);
    }

    /**
     * @return array
     */
    public function setOptionValueProvider()
    {
        return array(
            array(AbstractPasswordGeneratorClass::OPTION_TEST_BOOLEAN, true),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_INTEGER, 0),
        );
    }

    /**
     * @dataProvider setOptionExceptionProvider
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Invalid Option
     * @param $option
     * @param $value
     */
    public function testSetExceptionOption($option, $value)
    {
        $this->_object->setOptionValue($option, $value);
    }

    /**
     * @return array
     */
    public function setOptionExceptionProvider()
    {
        return array(
            array(AbstractPasswordGeneratorClass::OPTION_TEST_BOOLEAN, 99),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_BOOLEAN, null),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_BOOLEAN, 'test'),

            array(AbstractPasswordGeneratorClass::OPTION_TEST_INTEGER, true),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_INTEGER, null),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_INTEGER, 'test'),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_INTEGER, -101),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_INTEGER, 101),
        );
    }

    /**
     * @expectedException        \InvalidArgumentException
     */
    public function testUnknownSetOption()
    {
        $this->_object->setOption('unknown', array());
    }

    public function testUnknownOption()
    {
        $this->assertNull($this->_object->getOption('unknown', array()));
    }

    /**
     * @expectedException        \InvalidArgumentException
     */
    public function testUnknownSetOptionValue()
    {
        $this->_object->setOptionValue('unknown', true);
    }

    public function testUnknownOptionValue()
    {
        $this->assertNull($this->_object->getOptionValue('unknown', true));
    }

    public function testUnknownParameter()
    {
        $this->assertNull($this->_object->getParameter('unknown'));
    }

    /**
     * @dataProvider parameterProvider
     * @param $parameter
     * @param $value
     */
    public function testParameter($parameter, $value)
    {
        $this->_object->setParameter($parameter, $value);

        $this->assertEquals($value, $this->_object->getParameter($parameter));
    }

    public function parameterProvider()
    {
        return array(
            array('a', 1),
            array('ab', null),
            array('test', true),
            array('test2', 'value'),
        );
    }

    /**
     * @dataProvider validateValueProvider
     * @param $option
     * @param $value
     * @param $return
     */
    public function testValidateValue($option, $value, $return)
    {
        $this->assertEquals($return, $this->_object->validateValue($option, $value));
    }

    /**
     * @return array
     */
    public function validateValueProvider()
    {
        return array(
            array(AbstractPasswordGeneratorClass::OPTION_TEST_BOOLEAN, true, true),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_BOOLEAN, 1, false),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_BOOLEAN, null, false),

            array(AbstractPasswordGeneratorClass::OPTION_TEST_INTEGER, 0, true),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_INTEGER, 100, true),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_INTEGER, -100, true),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_INTEGER, true, false),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_INTEGER, null, false),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_INTEGER, '', false),

            array(AbstractPasswordGeneratorClass::OPTION_TEST_INTEGER_DEFAULT, 2147483647, true),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_INTEGER_DEFAULT, -2147483648, true),

            array(AbstractPasswordGeneratorClass::OPTION_TEST_STRING, 'abc', true),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_STRING, 'a', true),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_STRING, '', false),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_STRING, 0, false),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_STRING, null, false),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_STRING, true, false),

            array(AbstractPasswordGeneratorClass::OPTION_TEST_STRING, 'ac', true),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_STRING, 'abcd', false),
            array(AbstractPasswordGeneratorClass::OPTION_TEST_STRING, '', false),

            array('fail', '', false),
        );
    }

    /**
     * @dataProvider validateTypeProvider
     * @param $type
     * @param $return
     */
    public function testValidateType($type, $return)
    {
        $this->assertEquals($return, $this->_object->validateType($type));
    }

    /**
     * @return array
     */
    public function validateTypeProvider()
    {
        return array(
            array(AbstractPasswordGeneratorClass::TYPE_BOOLEAN, true),
            array(AbstractPasswordGeneratorClass::TYPE_INTEGER, true),
            array(AbstractPasswordGeneratorClass::TYPE_STRING, true),
            array('fail', false),
        );
    }
}
