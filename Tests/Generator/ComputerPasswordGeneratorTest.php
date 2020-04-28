<?php

declare(strict_types=1);

namespace Hackzilla\PasswordGenerator\Tests\Generator;

use InvalidArgumentException;
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use TypeError;

class ComputerPasswordGeneratorTest extends \PHPUnit\Framework\TestCase
{
    private $_object;

    /**
     *
     */
    public function setup(): void
    {
        $this->_object = new ComputerPasswordGenerator();

        $this->_object
            ->setOptionValue(ComputerPasswordGenerator::OPTION_UPPER_CASE, false)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_LOWER_CASE, false)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_NUMBERS, false)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_SYMBOLS, false)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_AVOID_SIMILAR, false);
    }

    /**
     * @dataProvider lengthProvider
     *
     * @param int $length
     */
    public function testLength($length): void
    {
        $this->_object->setLength($length);
        $this->assertSame($this->_object->getLength(), $length);
    }

    /**
     * @dataProvider lengthProvider
     *
     * @param int $length
     */
    public function testGeneratePassword($length): void
    {
        $this->_object
            ->setOptionValue(ComputerPasswordGenerator::OPTION_UPPER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_LOWER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_NUMBERS, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_SYMBOLS, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_AVOID_SIMILAR, true);

        $this->_object->setLength($length);
        $this->assertSame(\strlen($this->_object->generatePassword()), $length);
    }

    /**
     * @return array
     */
    public function lengthProvider()
    {
        return [
            [1],
            [4],
            [8],
            [16],
        ];
    }

    /**
     * @dataProvider getterSetterProvider
     *
     * @param string $method
     */
    public function testGetterSetters($method): void
    {
        $this->_object->{'set' . $method}(true);
        $this->assertTrue($this->_object->{'get' . $method}());

        $this->_object->{'set' . $method}(false);
        $this->assertTrue(!$this->_object->{'get' . $method}());
    }

    public function testNegativeLengthException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->_object->setLength(-1);
    }

    /**
     * @dataProvider      lengthExceptionProvider
     *
     * @param mixed $param
     */
    public function testLengthException($param): void
    {
        $this->expectException(TypeError::class);
        $this->_object->setLength($param);
    }

    public function lengthExceptionProvider()
    {
        return [
            ['a'],
            [false],
            [null],
        ];
    }

    /**
     * @dataProvider      getterSetterProvider
     *
     * @param string $method
     */
    public function testGetterSettersException(string $method): void
    {
        $this->expectException(TypeError::class);
        $this->_object->{'set' . $method}(1);
    }

    public function getterSetterProvider()
    {
        return [
            ['Uppercase'],
            ['Lowercase'],
            ['Numbers'],
            ['Symbols'],
            ['AvoidSimilar'],
        ];
    }

    /**
     * @dataProvider optionProvider
     *
     * @param string $option
     * @param array $exists
     * @param array $dontExist
     */
    public function testSetOption(string $option, array $exists, array $dontExist): void
    {
        $this->_object->setOptionValue($option, true);
        $availableCharacters = $this->_object->getCharacterList()->getCharacters();

        foreach ($exists as $check) {
            $this->assertStringContainsString($check, $availableCharacters);
        }
        foreach ($dontExist as $check) {
            $this->assertStringNotContainsString($check, $availableCharacters);
        }
    }

    /**
     * @return array
     */
    public function optionProvider()
    {
        return [
            [ComputerPasswordGenerator::OPTION_UPPER_CASE, ['A', 'B', 'C'], ['a', 'b', 'c']],
            [ComputerPasswordGenerator::OPTION_LOWER_CASE, ['a', 'b', 'c'], ['A', 'B', 'C']],
            [ComputerPasswordGenerator::OPTION_NUMBERS, ['1', '2', '3'], ['a', 'b', 'c', 'A', 'B', 'C']],
            [ComputerPasswordGenerator::OPTION_SYMBOLS, ['+', '=', '?'], ['a', 'b', 'c', 'A', 'B', 'C']],
        ];
    }

    public function testSetOptionSimilar(): void
    {
        $exists = ['a', 'b', 'c', 'A', 'B', 'C'];
        $dontExist = ['o', 'l', 'O'];

        $this->_object
            ->setOptionValue(ComputerPasswordGenerator::OPTION_UPPER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_LOWER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_AVOID_SIMILAR, true);

        $availableCharacters = $this->_object->getCharacterList()->getCharacters();

        foreach ($exists as $check) {
            $this->assertStringContainsString($check, $availableCharacters);
        }
        foreach ($dontExist as $check) {
            $this->assertStringNotContainsString($check, $availableCharacters);
        }
    }

    /**
     * @dataProvider optionsProvider
     *
     * @param string $option
     * @param string $parameter
     */
    public function testSetGet(string $option, string $parameter): void
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
        return [
            [ComputerPasswordGenerator::OPTION_UPPER_CASE, ComputerPasswordGenerator::PARAMETER_UPPER_CASE],
            [ComputerPasswordGenerator::OPTION_LOWER_CASE, ComputerPasswordGenerator::PARAMETER_LOWER_CASE],
            [ComputerPasswordGenerator::OPTION_NUMBERS, ComputerPasswordGenerator::PARAMETER_NUMBERS],
            [ComputerPasswordGenerator::OPTION_SYMBOLS, ComputerPasswordGenerator::PARAMETER_SYMBOLS],
        ];
    }

    /**
     *
     */
    public function testAvoidSimilar(): void
    {
        $this->_object
            ->setParameter(ComputerPasswordGenerator::PARAMETER_UPPER_CASE, 'AB')
            ->setParameter(ComputerPasswordGenerator::PARAMETER_SIMILAR, 'B')
            ->setOptionValue(ComputerPasswordGenerator::OPTION_UPPER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_AVOID_SIMILAR, true);

        $this->_object->setLength(4);

        $this->assertSame('AAAA', $this->_object->generatePassword());
    }

    /**
     *
     */
    public function testCharacterListException(): void
    {
        $this->expectException('\Hackzilla\PasswordGenerator\Exception\CharactersNotFoundException');
        $this->_object->getCharacterList();
    }
}
