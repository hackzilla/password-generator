<?php

namespace Hackzilla\PasswordGenerator\Generator;

use Hackzilla\PasswordGenerator\Exception\InvalidOptionException;

/**
 * Class RequirementPasswordGenerator
 *
 * Works just like ComputerPasswordGenerator with the addition of minimum and maximum counts.
 *
 * @package Hackzilla\PasswordGenerator\Generator
 */
class RequirementPasswordGenerator extends ComputerPasswordGenerator
{
    private $minimumCounts = array();
    private $maximumCounts = array();
    private $validOptions = array();

    /**
     */
    public function __construct()
    {
        parent::__construct();

        $this->validOptions = array(
            self::OPTION_UPPER_CASE,
            self::OPTION_LOWER_CASE,
            self::OPTION_NUMBERS,
            self::OPTION_SYMBOLS,
        );
    }

    /**
     * Generate one password based on options.
     *
     * @return string password
     */
    public function generatePassword()
    {
        $characterList = $this->getCharacterList()->getCharacters();
        $characters = \strlen($characterList);
        $password = '';

        $length = $this->getLength();

        for ($i = 0; $i < $length; ++$i) {
            $password .= $characterList[$this->randomInteger(0, $characters - 1)];
        }

        return $password;
    }

    /**
     * Password minimum count for option.
     *
     * @param string $option Use class constants
     *
     * @return int|null
     */
    public function getMinimumCount($option)
    {
        return isset($this->minimumCounts[$option]) ? $this->minimumCounts[$option] : null;
    }

    /**
     * Password maximum count for option.
     *
     * @param string $option Use class constants
     *
     * @return int|null
     */
    public function getMaximumCount($option)
    {
        return isset($this->maximumCounts[$option]) ? $this->maximumCounts[$option] : null;
    }

    /**
     * Set minimum count of option for desired password(s).
     *
     * @param string   $option Use class constants
     * @param int|null $characterCount
     *
     * @return $this
     *
     * @throws InvalidOptionException
     */
    public function setMinimumCount($option, $characterCount)
    {
        if (!$this->validOption($option)) {
            throw new InvalidOptionException('Invalid Option');
        }

        if (is_null($characterCount)) {
            unset($this->minimumCounts[$option]);

            return $this;
        }

        if (!is_int($characterCount) || $characterCount < 0) {
            throw new \InvalidArgumentException('Expected non-negative integer');
        }

        $this->minimumCounts[$option] = $characterCount;

        return $this;
    }

    /**
     * Set maximum count of option for desired password(s).
     *
     * @param string   $option Use class constants
     * @param int|null $characterCount
     *
     * @return $this
     *
     * @throws InvalidOptionException
     */
    public function setMaximumCount($option, $characterCount)
    {
        if (!$this->validOption($option)) {
            throw new InvalidOptionException('Invalid Option');
        }

        if (is_null($characterCount)) {
            unset($this->maximumCounts[$option]);

            return $this;
        }

        if (!is_int($characterCount) || $characterCount < 0) {
            throw new \InvalidArgumentException('Expected non-negative integer');
        }

        $this->maximumCounts[$option] = $characterCount;

        return $this;
    }

    public function validLimits()
    {
        $total = 0;

        foreach ($this->minimumCounts as $minOption => $minCount) {
            $total += $minCount;
        }

        if ($total > $this->getLength()) {
            return false;
        }

        foreach ($this->minimumCounts as $minOption => $minCount) {
            $total = $minCount;

            foreach ($this->maximumCounts as $maxOption => $maxCount) {
                if ($minOption !== $maxOption) {
                    $total += $maxCount;
                }
            }

            if ($total > $this->getLength()) {
                return false;
            }
        }

        foreach ($this->maximumCounts as $maxOption => $maxCount) {
            $total = $maxCount;

            foreach ($this->minimumCounts as $minOption => $minCount) {
                if ($minOption !== $maxOption) {
                    $total += $minCount;
                }
            }

            if ($total > $this->getLength()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $option
     *
     * @return bool
     */
    public function validOption($option)
    {
        return in_array($option, $this->validOptions, true);
    }

    /**
     * Generate $count number of passwords.
     *
     * @param int $count Number of passwords to return
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function generatePasswords($count = 1)
    {
        if (!is_int($count)) {
            throw new \InvalidArgumentException('Expected integer');
        } elseif ($count < 0) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $i = 0;
        $passwords = array();

        while ($i < $count) {
            $password = $this->generatePassword();

            if (!$this->validatePassword($password)) {
                continue;
            }

            $i++;
            $passwords[] = $password;
        }

        return $passwords;
    }

    /**
     * Check password is valid when comparing to minimum and maximum counts of options.
     *
     * @param string $password
     *
     * @return bool
     */
    public function validatePassword($password)
    {
        foreach ($this->validOptions as $option) {
            $minCount = $this->getMinimumCount($option);
            $maxCount = $this->getMaximumCount($option);
            $count = strlen(preg_replace('|[^'.preg_quote($this->getParameter($option)).']|', '', $password));

            if (!is_null($minCount) && $count < $minCount) {
                return false;
            }

            if (!is_null($maxCount) && $count > $maxCount) {
                return false;
            }
        }

        return true;
    }
}
