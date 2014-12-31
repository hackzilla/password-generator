<?php

namespace Hackzilla\PasswordGenerator\Generator;

abstract class AbstractPasswordGenerator implements PasswordGeneratorInterface
{
    private $options = array();
    private $optionValues = array();
    private $parameters = array();

    const TYPE_BOOLEAN = 'boolean';
    const TYPE_INTEGER = 'integer';
    const TYPE_STRING = 'string';

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
     * Set password generator option
     *
     * @param string $option
     * @param array $optionSettings
     *
     * @return $this
     */
    public function setOption($option, $optionSettings)
    {
        if (!isset($optionSettings['type']) || !$this->validateType($optionSettings['type'])) {
            throw new \InvalidArgumentException('Invalid Option Type');
        }

        switch ($optionSettings['type']) {
            case self::TYPE_INTEGER:
                if (!isset($optionSettings['min'])) {
                    $optionSettings['min'] = ~PHP_INT_MAX;
                }
                if (!isset($optionSettings['max'])) {
                    $optionSettings['max'] = PHP_INT_MAX;
                }
                break;

            case self::TYPE_STRING:
                if (!isset($optionSettings['min'])) {
                    $optionSettings['min'] = 0;
                }
                if (!isset($optionSettings['max'])) {
                    $optionSettings['max'] = 255;
                }
                break;
        }

        $this->options[$option] = $optionSettings;

        return $this;
    }

    /**
     * Get option
     *
     * @param $option
     *
     * @return null
     */
    public function getOption($option)
    {
        if (!isset($this->options[$option])) {
            return null;
        }

        return $this->options[$option];
    }

    /**
     * Set password generator option value
     *
     * @param string $option
     * @param $value
     *
     * @return $this
     */
    public function setOptionValue($option, $value)
    {
        if (!isset($this->options[$option]) || !is_array($this->options[$option])) {
            throw new \InvalidArgumentException('Invalid Option');
        }

        if (!$this->validateValue($option, $value)) {
            throw new \InvalidArgumentException('Invalid Option Value');
        }

        $this->optionValues[$option] = $value;

        return $this;
    }

    /**
     * Get option value
     *
     * @param $option
     *
     * @return mixed
     */
    public function getOptionValue($option)
    {
        if (!isset($this->optionValues[$option])) {
            if (isset($this->options[$option]['default'])) {
                return $this->options[$option]['default'];
            }

            return null;
        }

        return $this->optionValues[$option];
    }

    /**
     * @param string $parameter
     * @param mixed $value
     *
     * @return $this
     */
    public function setParameter($parameter, $value)
    {
        $this->parameters[$parameter] = $value;

        return $this;
    }

    /**
     * @param string $parameter
     * @param mixed $default
     *
     * @return null|mixed
     */
    public function getParameter($parameter, $default = null)
    {
        if (!isset($this->parameters[$parameter])) {
            return $default;
        }

        return $this->parameters[$parameter];
    }

    /**
     * Possible options
     *
     * @return array
     */
    public function getPossibleOptions()
    {
        return $this->options;
    }

    public function validateType($type)
    {
        switch ($type) {
            case self::TYPE_BOOLEAN:
            case self::TYPE_INTEGER:
            case self::TYPE_STRING:
                return true;
        }

        return false;
    }

    public function validateValue($option, $value)
    {
        $optionSettings = $this->getOption($option);

        switch ($optionSettings['type']) {
            case self::TYPE_BOOLEAN:
                return is_bool($value);

            case self::TYPE_INTEGER:
                /* check within min / max */
                if ($optionSettings['min'] > $value || $optionSettings['max'] < $value) {
                    return false;
                }

                return is_int($value);

            case self::TYPE_STRING:
                /* check length within min / max */
                if ($optionSettings['min'] > strlen($value) || $optionSettings['max'] < strlen($value)) {
                    return false;
                }

                return is_string($value);
        }

        return false;
    }
}
