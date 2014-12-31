<?php

namespace Hackzilla\PasswordGenerator\Generator;

class DummyPasswordGenerator extends AbstractPasswordGenerator
{
    const OPTION_LENGTH = 'LENGTH';

    /**
     */
    public function __construct()
    {
        $this
            ->setOption(self::OPTION_LENGTH, array('type' => self::TYPE_INTEGER, 'default' => 10))
        ;
    }

    public function generatePassword()
    {
        $length = $this->getOptionValue(self::OPTION_LENGTH);

        if ($length < 8) {
            return \substr('password', 0, $length);
        }

        return str_pad('password', $length, '?');
    }

    /**
     * Password length
     *
     * @return integer
     */
    public function getLength()
    {
        return $this->getOptionValue(self::OPTION_LENGTH);
    }

    /**
     * Set length of desired password(s)
     *
     * @param integer $characterCount
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setLength($characterCount)
    {
        if (!is_int($characterCount) || $characterCount < 1) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->setOptionValue(self::OPTION_LENGTH, $characterCount);

        return $this;
    }
}
