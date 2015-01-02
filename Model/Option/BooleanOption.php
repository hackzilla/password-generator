<?php

namespace Hackzilla\PasswordGenerator\Model\Option;

use InvalidArgumentException;

class BooleanOption extends Option
{
    /**
     * Set option boolean value
     *
     * @param boolean $value
     * @throws \InvalidArgumentException
     */
    public function setValue($value)
    {
        if (!is_bool($value)) {
            throw new InvalidArgumentException('Boolean required');
        }

        parent::setValue($value);
    }

    public function getType()
    {
        return self::TYPE_BOOLEAN;
    }
}
