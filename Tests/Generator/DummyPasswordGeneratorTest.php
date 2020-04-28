<?php

declare(strict_types=1);

namespace Hackzilla\PasswordGenerator\Tests\Generator;

use Hackzilla\PasswordGenerator\Generator\DummyPasswordGenerator;

class DummyPasswordGeneratorTest extends \PHPUnit\Framework\TestCase
{
    /** @var DummyPasswordGenerator */
    private $_object;

    /**
     *
     */
    public function setup(): void
    {
        $this->_object = new DummyPasswordGenerator();
    }

    /**
     * @dataProvider lengthProvider
     *
     * @param int $length
     * @param string $comparePassword
     */
    public function testGeneratePasswords($length, $comparePassword): void
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
     * @param int $length
     * @param string $password
     */
    public function testGeneratePassword($length, $password)
    {
        $this->_object->setOptionValue(DummyPasswordGenerator::OPTION_LENGTH, $length);
        $this->assertSame($this->_object->generatePassword(), $password);
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

    public function testNegativeLengthException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->_object->setLength(-1);
    }

    /**
     * @dataProvider      lengthExceptionProvider
     *
     * @param mixed $param
     */
    public function testLengthException($param): void
    {
        $this->expectException(\TypeError::class);
        $this->_object->setLength($param);
    }

    public function lengthExceptionProvider()
    {
        return array(
            array('a'),
            array(false),
            array(null),
        );
    }
}
