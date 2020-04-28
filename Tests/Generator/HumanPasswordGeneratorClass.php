<?php

declare(strict_types=1);

namespace Hackzilla\PasswordGenerator\Tests\Generator;

use Hackzilla\PasswordGenerator\Generator\HumanPasswordGenerator;

class HumanPasswordGeneratorClass extends HumanPasswordGenerator
{
    public function generateWordList() : array
    {
        return array();
    }
}
