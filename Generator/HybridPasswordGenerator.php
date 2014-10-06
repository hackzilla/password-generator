<?php

namespace Hackzilla\PasswordGenerator\Generator;

use Hackzilla\PasswordGenerator\Exception\CharactersNotFoundException;

class HybridPasswordGenerator implements PasswordGeneratorInterface
{

    private $_segmentCount = 4;
    private $_segmentLength = 3;
    private $_segmentSeparator = '-';
    private $_selectedOptions;
    private $_uppercaseLetters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    private $_lowercaseLetters = 'abcdefghijklmnopqrstuvwxyz';
    private $_numbers = '0123456789';

    const OPTION_UPPER_CASE = 1;
    const OPTION_LOWER_CASE = 2;
    const OPTION_NUMBERS = 4;

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

        if ($this->_selectedOptions & self::OPTION_UPPER_CASE) {
            $characters .= $this->getUppercaseLetters();
        }

        if ($this->_selectedOptions & self::OPTION_LOWER_CASE) {
            $characters .= $this->getLowercaseLetters();
        }

        if ($this->_selectedOptions & self::OPTION_NUMBERS) {
            $characters .= $this->getNumbers();
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

        for ($i = 0; $i < $this->_segmentCount; $i++) {
            if ($password) {
                $password .= $this->getSegmentSeparator();
            }

            for ($j = 0; $j < $this->getSegmentLength(); $j++) {
                $password .= $characterList[mt_rand(0, $characters - 1)];
            }
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

    /**
     * Get number of words in desired password
     *
     * @return integer
     */
    public function getLength()
    {
        return $this->getSegementCount();
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
        $this->setSegmentCount($characterCount);

        return $this;
    }

    /**
     * Get number of segments in desired password
     *
     * @return integer
     */
    public function getSegementCount()
    {
        return $this->_segmentCount;
    }

    /**
     * Set number of segments in desired password(s)
     *
     * @param integer $segmentCount
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setSegmentCount($characterCount)
    {
        if (!is_int($characterCount) || $characterCount < 1) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->_segmentCount = $characterCount;

        return $this;
    }

    /**
     * Get number of segments in desired password
     *
     * @return integer
     */
    public function getSegmentLength()
    {
        return $this->_segmentLength;
    }

    /**
     * Set length of segment
     *
     * @param integer $segmentLength
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setSegmentLength($segmentLength)
    {
        if (!is_int($segmentLength) || $segmentLength < 1) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->_segmentLength = $segmentLength;

        return $this;
    }

    /**
     * Get Segment Separator
     *
     * @return string
     */
    public function getSegmentSeparator()
    {
        return $this->_segmentSeparator;
    }

    /**
     * Set segment separator
     *
     * @param type $segmentSeparator
     *
     * @return \Hackzilla\PasswordGenerator\Generator\HybridPasswordGenerator
     * @throws \InvalidArgumentException
     */
    public function setSegmentSeparator($segmentSeparator)
    {
        if (!is_string($segmentSeparator)) {
            throw new \InvalidArgumentException('Expected string');
        }

        $this->_segmentSeparator = $segmentSeparator;

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

}
