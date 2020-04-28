<?php

declare(strict_types=1);

namespace Hackzilla\PasswordGenerator\Tests\Model\Option;

use Hackzilla\PasswordGenerator\Model\Option\Option;

class OptionClass extends Option
{
    public function getType() : string
    {
        return '';
    }
}
