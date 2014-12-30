<?php

namespace Hackzilla\PasswordGenerator\Tests\Generator;

use Hackzilla\PasswordGenerator\Generator\AbstractPasswordGenerator;

class AbstractPasswordGeneratorClass extends AbstractPasswordGenerator
{
    const OPTION_TEST_BOOLEAN = 'test1';
    const OPTION_TEST_INTEGER = 'test2';
    const OPTION_TEST_INTEGER_DEFAULT = 'test3';

    public function __construct()
    {
        $this
            ->setOption(self::OPTION_TEST_BOOLEAN, array('type' => self::TYPE_BOOLEAN))
            ->setOption(self::OPTION_TEST_INTEGER, array('type' => self::TYPE_INTEGER, 'min' => -100, 'max' => 100))
            ->setOption(self::OPTION_TEST_INTEGER_DEFAULT, array('type' => self::TYPE_INTEGER, 'default' => 99));
    }

    public function generatePassword()
    {
        return '';
    }
}
