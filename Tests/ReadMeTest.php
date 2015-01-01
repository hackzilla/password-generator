<?php

namespace Hackzilla\PasswordGenerator\Tests;

use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;
use Hackzilla\PasswordGenerator\Generator\HybridPasswordGenerator;

class ReadMeTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleUsage()
    {
        $generator = new ComputerPasswordGenerator();

        $generator
            ->setOptionValue(ComputerPasswordGenerator::OPTION_UPPER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_LOWER_CASE, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_NUMBERS, true)
            ->setOptionValue(ComputerPasswordGenerator::OPTION_SYMBOLS, false)
        ;

        $generator->generatePassword();
    }

    public function testMorePasswordsUsage()
    {
        $generator = new ComputerPasswordGenerator();

        $generator
            ->setUppercase()
            ->setLowercase()
            ->setNumbers()
            ->setSymbols(false)
            ->setLength(12);

        $password = $generator->generatePasswords(10);
    }

    public function testHybridPasswordGeneratorUsage()
    {
        $generator = new HybridPasswordGenerator();

        $generator
            ->setUppercase()
            ->setLowercase()
            ->setNumbers()
            ->setSymbols(false)
            ->setSegmentLength(3)
            ->setSegmentCount(4)
            ->setSegmentSeparator('-');

        $password = $generator->generatePasswords(10);
    }
}
