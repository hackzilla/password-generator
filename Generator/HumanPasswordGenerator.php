<?php

namespace Hackzilla\PasswordGenerator\Generator;

use Hackzilla\PasswordGenerator\Exception\FileNotFoundException;
use Hackzilla\PasswordGenerator\Exception\WordsNotFoundException;

class HumanPasswordGenerator implements PasswordGeneratorInterface
{

    private $wordList;
    private $length = 4;
    private $minWordLength = 3;
    private $maxWordLength = 99;

    public function __construct($options = null)
    {
        
    }

    /**
     * Possible options
     * 
     * @return array
     */
    public function getPossibleOptions()
    {
        return array();
    }

    /**
     * Lookup options key value
     *
     * @param int $option
     * @return null|string
     */
    public function getOptionKey($option)
    {
        return null;
    }

    /**
     * Generate character list for us in generating passwords
     *
     * @return string Character list
     * @throws WordsNotFoundException
     */
    public function generateWordList()
    {
        $words = explode("\n", \file_get_contents($this->getWordList()));

        foreach ($words as $i => $word) {
            if (\strlen($word) > $this->maxWordLength || \strlen($word) < $this->minWordLength) {
                unset($words[$i]);
            }
        }

        $words = \array_values($words);

        if (!$words) {
            throw new WordsNotFoundException('No words selected.');
        }

        return $words;
    }

    /**
     * Generate one password based on options
     * 
     * @return string password
     * @throws WordsNotFoundException
     */
    public function generatePassword()
    {
        $wordList = $this->generateWordList();

        $words = \count($wordList);

        if (!$words) {
            throw new WordsNotFoundException('No words selected.');
        }

        $password = '';

        for ($i = 0; $i < $this->length; $i++) {
            $password .= $wordList[mt_rand(0, $words - 1)];
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
        return $this;
    }

    /**
     * Get number of words in desired password
     * 
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * Set number of words in desired password(s)
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

    /**
     * get max word length
     *
     * @return integer
     */
    public function getMaxWordLength()
    {
        return $this->maxWordLength;
    }

    /**
     * set max word length
     *
     * @param integer $length
     * @return \Hackzilla\PasswordGenerator\Generator\HumanPasswordGenerator
     * @throws \InvalidArgumentException
     */
    public function setMaxWordLength($length)
    {
        if (!is_int($length) || $length < 1) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->maxWordLength = $length;

        return $this;
    }

    /**
     * get min word length
     *
     * @return integer
     */
    public function getMinWordLength()
    {
        return $this->minWordLength;
    }

    /**
     * set min word length
     *
     * @param integer $length
     * @return \Hackzilla\PasswordGenerator\Generator\HumanPasswordGenerator
     * @throws \InvalidArgumentException
     */
    public function setMinWordLength($length)
    {
        if (!is_int($length) || $length < 1) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->minWordLength = $length;

        return $this;
    }

    /**
     * Set word list
     *
     * @param string $filename
     * @return \Hackzilla\PasswordGenerator\Generator\HumanPasswordGenerator
     * @throws \InvalidArgumentException
     * @throws FileNotFoundException
     */
    public function setWordList($filename)
    {
        if (!is_string($filename)) {
            throw new \InvalidArgumentException('Expected string');
        } else if (!file_exists($filename)) {
            throw new FileNotFoundException('File not found');
        }

        $this->wordList = $filename;

        return $this;
    }

    /**
     * Get word list filename
     *
     * @return string
     */
    public function getWordList()
    {
        return $this->wordList;
    }

}
