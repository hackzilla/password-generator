<?php

namespace Hackzilla\PasswordGenerator\Generator;


abstract class AbstractPasswordGenerator implements PasswordGeneratorInterface
{
    private $_selectedOptions;

    static public $options = array();

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
        } else if ($count < 0) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $passwords = array();

        for ($i = 0; $i < $count; $i++) {
            $passwords[] = $this->generatePassword();
        }

        return $passwords;
    }

    /**
     * Set password generator options
     *
     * @param integer $options
     *
     * @return $this
     */
    public function setOptions($options)
    {
        if (!is_int($options)) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->_selectedOptions = $options;

        return $this;
    }

    public function getOption($option)
    {
        return $this->_selectedOptions & $option;
    }

    /**
     * Possible options
     *
     * @return array
     */
    public function getPossibleOptions()
    {
        return self::$options;
    }

    /**
     * Lookup options key value
     *
     * @param int $option
     * @return null|string
     */
    public function getOptionKey($option)
    {
        if (isset(self::$options[$option])) {
            return self::$options[$option]['key'];
        }

        return null;
    }
}
