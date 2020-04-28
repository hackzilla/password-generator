<?php

declare(strict_types=1);

namespace Hackzilla\PasswordGenerator\Model\Option;

use InvalidArgumentException;

class BooleanOption extends Option
{
    /**
     * @param bool $value
     */
    public function setValue($value)
    {
        if (!is_bool($value)) {
            throw new InvalidArgumentException('Boolean required');
        }

        parent::setValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getType() : string
    {
        return self::TYPE_BOOLEAN;
    }
}
