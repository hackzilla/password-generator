<?php

namespace Hackzilla\PasswordGenerator\Generator;

class DummyPasswordGenerator implements PasswordGeneratorInterface
{

    private $length;
    private $options;

    public function generatePassword()
    {
        if ($this->length < 8) {
            return \substr('password', 0, $this->length);
        }

        return str_pad('password', $this->length, '?');
    }

    /**
     * Generate $count number of passwords
     *
     * @param integer $count Number of passwords to return
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function generatePasswords($count = 1)
    {
        if (!is_int($count)) {
            throw new \InvalidArgumentException('Expected integer');
        }

        $passwords = array();

        for ($i = 0; $i < $count; $i++) {
            $passwords[] = $this->generatePassword();
        }

        return $passwords;
    }

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
