<?php

namespace Hackzilla\PasswordGenerator\Generator;

class DummyPasswordGenerator extends AbstractPasswordGenerator implements PasswordGeneratorInterface
{
    private $length;

    /**
     * @return array
     */
    public function getPossibleOptions()
    {
        return array();
    }

    /**
     * @return string
     */
    public function generatePassword()
    {
        if ($this->length < 8) {
            return \substr('password', 0, $this->length);
        }

        return str_pad('password', $this->length, '?');
    }

    /**
     * @param $options
     */
    public function setOptions($options)
    {

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
        if (!is_int($characterCount)) {
            throw new \InvalidArgumentException('Expected integer');
        }

        $this->length = $characterCount;

        return $this;
    }
}
