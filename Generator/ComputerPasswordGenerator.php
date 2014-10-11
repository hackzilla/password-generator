<?php

namespace Hackzilla\PasswordGenerator\Generator;

use Hackzilla\PasswordGenerator\Exception\CharactersNotFoundException;

class ComputerPasswordGenerator extends AbstractPasswordGenerator implements PasswordGeneratorInterface
{

    private $_length = 8;
    private $_selectedOptions;
    private $_uppercaseLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private $_lowercaseLetters = 'abcdefghijklmnopqrstuvwxyz';
    private $_numbers = '0123456789';
    private $_symbols = '!@$%^&*()<>,.?/[]{}-=_+';
    private $_avoidSimilar = 'lOo';

    const OPTION_UPPER_CASE = 1;
    const OPTION_LOWER_CASE = 2;
    const OPTION_NUMBERS = 4;
    const OPTION_SYMBOLS = 8;
    const OPTION_AVOID_SIMILAR = 16;

    static public $options = array(
        self::OPTION_UPPER_CASE => array(
            'key' => 'includeUppercase',
            'label' => 'Include Uppercase',
        ),
        self::OPTION_LOWER_CASE => array(
            'key' => 'includeLowercase',
            'label' => 'Include Lowercase',
        ),
        self::OPTION_NUMBERS => array(
            'key' => 'includeNumbers',
            'label' => 'Include Numbers',
        ),
        self::OPTION_SYMBOLS => array(
            'key' => 'includeSymbols',
            'label' => 'Include Symbols',
        ),
        self::OPTION_AVOID_SIMILAR => array(
            'key' => 'avoidSimilarCharacters',
            'label' => 'Avoid Similar Characters',
        ),
    );

    public function __construct($options = null)
    {
        if (\is_null($options)) {
            $options = self::OPTION_UPPER_CASE | self::OPTION_LOWER_CASE | self::OPTION_NUMBERS;
        }

        $this->setOptions($options);
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

    /**
     * Generate character list for us in generating passwords
     *
     * @return string Character list
     * @throws CharactersNotFoundException
     */
    public function getCharacterList()
    {
        $characters = '';

        if ($this->getOption(self::OPTION_UPPER_CASE)) {
            $characters .= $this->getUppercaseLetters();
        }

        if ($this->getOption(self::OPTION_LOWER_CASE)) {
            $characters .= $this->getLowercaseLetters();
        }

        if ($this->getOption(self::OPTION_NUMBERS)) {
            $characters .= $this->getNumbers();
        }

        if ($this->getOption(self::OPTION_SYMBOLS)) {
            $characters .= $this->getSymbols();
        }

        if ($this->getOption(self::OPTION_AVOID_SIMILAR)) {
            $removeCharacters = \str_split($this->getAvoidSimiliar());
            $characters = \str_replace($removeCharacters, '', $characters);
        }

        if (!$characters) {
            throw new CharactersNotFoundException('No character sets selected.');
        }

        return $characters;
    }

    /**
     * Generate one password based on options
     * 
     * @return string password
     */
    public function generatePassword()
    {
        $characterList = $this->getCharacterList();
        $characters = \strlen($characterList);
        $password = '';

        for ($i = 0; $i < $this->_length; $i++) {
            $password .= $characterList[mt_rand(0, $characters - 1)];
        }

        return $password;
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

        $this->_length = $characterCount;

        return $this;
    }

    /**
     * Get Uppercase characters
     *
     * @return string
     */
    public function getUppercaseLetters()
    {
        return $this->_uppercaseLetters;
    }

    /**
     * Set characters to use for uppercase characters
     *
     * @param string $characters
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setUppercaseLetters($characters)
    {
        if (!is_string($characters)) {
            throw new \InvalidArgumentException('Expected string containing Uppercase letters');
        }

        $this->_uppercaseLetters = $characters;

        return $this;
    }

    /**
     * Get Lowercase characters
     *
     * @return string
     */
    public function getLowercaseLetters()
    {
        return $this->_lowercaseLetters;
    }

    /**
     * Set characters to use for lowercase characters
     *
     * @param string $characters
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setLowercaseLetters($characters)
    {
        if (!is_string($characters)) {
            throw new \InvalidArgumentException('Expected string containing Lowercase letters');
        }

        $this->_lowercaseLetters = $characters;

        return $this;
    }

    /**
     * Get Number characters
     *
     * @return string
     */
    public function getNumbers()
    {
        return $this->_numbers;
    }

    /**
     * Set characters to use for number characters
     *
     * @param string $characters
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setNumbers($characters)
    {
        if (!is_string($characters)) {
            throw new \InvalidArgumentException('Expected string containing Numbers');
        }

        $this->_numbers = $characters;

        return $this;
    }

    /**
     * Get Symbol characters
     *
     * @return string
     */
    public function getSymbols()
    {
        return $this->_symbols;
    }

    /**
     * Set characters to use for symbol characters
     *
     * @param string $characters
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setSymbols($characters)
    {
        if (!is_string($characters)) {
            throw new \InvalidArgumentException('Expected string containing Symbols');
        }

        $this->_symbols = $characters;

        return $this;
    }

    /**
     * Get characters to remove that are similar
     *
     * @return string
     */
    public function getAvoidSimiliar()
    {
        return $this->_avoidSimilar;
    }

    /**
     * Set characters to be removed when avoiding similar characters
     *
     * @param string $characters
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setAvoidSimiliar($characters)
    {
        if (!is_string($characters)) {
            throw new \InvalidArgumentException('Expected string containing characters to remove');
        }

        $this->_avoidSimilar = $characters;

        return $this;
    }

}
