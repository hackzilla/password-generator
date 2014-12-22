<?php

namespace Hackzilla\PasswordGenerator\Generator;

use Hackzilla\PasswordGenerator\Exception\FileNotFoundException;
use Hackzilla\PasswordGenerator\Exception\WordsNotFoundException;

class HumanPasswordGenerator extends AbstractPasswordGenerator
{
    private $_wordCache;
    private $wordList;
    private $wordSeparator = '';
    private $length = 4;
    private $minWordLength = 3;
    private $maxWordLength = 99;

    /**
     * Generate character list for us in generating passwords
     *
     * @return string Character list
     * @throws WordsNotFoundException
     */
    public function generateWordList()
    {
        if (!is_null($this->_wordCache)) {
            return $this->_wordCache;
        }

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

        $this->_wordCache = $words;

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
            if ($i) {
                $password .= $this->wordSeparator;
            }

            $password .= $wordList[mt_rand(0, $words - 1)];
        }

        return $password;
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
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setMaxWordLength($length)
    {
        if (!is_int($length) || $length < 1) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->maxWordLength = $length;
        $this->_wordCache = null;

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
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setMinWordLength($length)
    {
        if (!is_int($length) || $length < 1) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->minWordLength = $length;
        $this->_wordCache = null;

        return $this;
    }

    /**
     * Set word list
     *
     * @param string $filename
     * @return $this
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
        $this->_wordCache = null;

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

    /**
     * Get word separator
     *
     * @return string
     */
    public function getWordSeparator()
    {
        return $this->wordSeparator;
    }

    /**
     * Set word separator
     *
     * @param string $separator
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setWordSeparator($separator)
    {
        if (!is_string($separator)) {
            throw new \InvalidArgumentException('Expected string');
        }

        $this->wordSeparator = $separator;

        return $this;
    }
}
