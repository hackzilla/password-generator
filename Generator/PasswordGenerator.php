<?php

namespace Hackzilla\PasswordGenerator\Generator;

class PasswordGenerator implements PasswordGeneratorInterface
{

    private $length = 8;
    private $selectedOptions;

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
     * @throws \Exception
     */
    public function getCharacterList()
    {
        $characters = '';

        if ($this->selectedOptions & self::OPTION_UPPER_CASE) {
            $characters .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }

        if ($this->selectedOptions & self::OPTION_LOWER_CASE) {
            $characters .= 'abcdefghijklmnopqrstuvwxyz';
        }

        if ($this->selectedOptions & self::OPTION_NUMBERS) {
            $characters .= '0123456789';
        }

        if ($this->selectedOptions & self::OPTION_SYMBOLS) {
            $characters .= '!@$%^&*()<>,.?/[]{}-=_+';
        }

        if ($this->selectedOptions & self::OPTION_AVOID_SIMILAR) {
            $characters = \str_replace(array('l', 'O', 'o'), '', $characters);
        }

        if (!$characters) {
            throw new \Exception('No character sets selected.');
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

        for ($i = 0; $i < $this->length; $i++) {
            $password .= $characterList[mt_rand(0, $characters - 1)];
        }

        return $password;
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

    /**
     * Set password generator options
     *
     * @param integer $options
     *
     * @return \Hackzilla\PasswordGenerator\PasswordGenerator
     */
    public function setOptions($options)
    {
        if (!is_int($options)) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->selectedOptions = $options;

        return $this;
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

        $this->length = $characterCount;

        return $this;
    }

}
