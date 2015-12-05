<?php

namespace Hackzilla\PasswordGenerator\Generator;

use Hackzilla\PasswordGenerator\Exception\FileNotFoundException;
use Hackzilla\PasswordGenerator\Exception\ImpossiblePasswordLengthException;
use Hackzilla\PasswordGenerator\Exception\WordsNotFoundException;
use Hackzilla\PasswordGenerator\Model\Option\Option;

class HumanPasswordGenerator extends AbstractPasswordGenerator
{
    const OPTION_WORDS = 'WORDS';
    const OPTION_MIN_WORD_LENGTH = 'MIN';
    const OPTION_MAX_WORD_LENGTH = 'MAX';
    const OPTION_LENGTH = 'LENGTH';

    const PARAMETER_DICTIONARY_FILE = 'DICTIONARY';
    const PARAMETER_WORD_CACHE = 'CACHE';
    const PARAMETER_WORD_SEPARATOR = 'SEPARATOR';

    public function __construct()
    {
        $this
            ->setOption(self::OPTION_LENGTH, array('type' => Option::TYPE_INTEGER, 'default' => null))
            ->setOption(self::OPTION_WORDS, array('type' => Option::TYPE_INTEGER, 'default' => 4))
            ->setOption(self::OPTION_MIN_WORD_LENGTH, array('type' => Option::TYPE_INTEGER, 'default' => 3))
            ->setOption(self::OPTION_MAX_WORD_LENGTH, array('type' => Option::TYPE_INTEGER, 'default' => 20))
            ->setParameter(self::PARAMETER_WORD_SEPARATOR, '')
        ;
    }

    /**
     * Generate character list for us in generating passwords.
     *
     * @return string Character list
     *
     * @throws WordsNotFoundException
     */
    public function generateWordList()
    {
        if ($this->getParameter(self::PARAMETER_WORD_CACHE) !== null) {
            return $this->getParameter(self::PARAMETER_WORD_CACHE);
        }

        $words = explode("\n", \file_get_contents($this->getWordList()));

        $minWordLength = $this->getOptionValue(self::OPTION_MIN_WORD_LENGTH);
        $maxWordLength = $this->getOptionValue(self::OPTION_MAX_WORD_LENGTH);

        foreach ($words as $i => $word) {
            if (\strlen($word) > $maxWordLength || \strlen($word) < $minWordLength) {
                unset($words[$i]);
            }
        }

        $words = \array_values($words);

        if (!$words) {
            throw new WordsNotFoundException('No words selected.');
        }

        $this->setParameter(self::PARAMETER_WORD_CACHE, $words);

        return $words;
    }

    /**
     * Generate one password based on options.
     *
     * @return string password
     *
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
        $wordCount = $this->getOptionValue(self::OPTION_WORDS);

        for ($i = 0; $i < $wordCount; ++$i) {
            if ($i) {
                $password .= $this->getParameter(self::PARAMETER_WORD_SEPARATOR);
            }

            $password .= $wordList[$this->randomInteger(0, $words - 1)];
        }

        return $password;
    }

    /**
     * Get number of words in desired password.
     *
     * @return int
     */
    public function getWordCount()
    {
        return $this->getOptionValue(self::OPTION_WORDS);
    }

    /**
     * Set number of words in desired password(s).
     *
     * @param int $characterCount
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setWordCount($characterCount)
    {
        if (!is_int($characterCount) || $characterCount < 1) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->setOptionValue(self::OPTION_WORDS, $characterCount);

        return $this;
    }

    /**
     * get max word length.
     *
     * @return int
     */
    public function getMaxWordLength()
    {
        return $this->getOptionValue(self::OPTION_MAX_WORD_LENGTH);
    }

    /**
     * set max word length.
     *
     * @param int $length
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setMaxWordLength($length)
    {
        if (!is_int($length) || $length < 1) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->setOptionValue(self::OPTION_MAX_WORD_LENGTH, $length);
        $this->setParameter(self::PARAMETER_WORD_CACHE, null);

        return $this;
    }

    /**
     * get min word length.
     *
     * @return int
     */
    public function getMinWordLength()
    {
        return $this->getOptionValue(self::OPTION_MIN_WORD_LENGTH);
    }

    /**
     * set min word length.
     *
     * @param int $length
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setMinWordLength($length)
    {
        if (!is_int($length) || $length < 1) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->setOptionValue(self::OPTION_MIN_WORD_LENGTH, $length);
        $this->setParameter(self::PARAMETER_WORD_CACHE, null);

        return $this;
    }

    /**
     * Set word list.
     *
     * @param string $filename
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     * @throws FileNotFoundException
     */
    public function setWordList($filename)
    {
        if (!is_string($filename)) {
            throw new \InvalidArgumentException('Expected string');
        } elseif (!file_exists($filename)) {
            throw new FileNotFoundException('File not found');
        }

        $this->setParameter(self::PARAMETER_DICTIONARY_FILE, $filename);
        $this->setParameter(self::PARAMETER_WORD_CACHE, null);

        return $this;
    }

    /**
     * Get word list filename.
     *
     * @throws FileNotFoundException
     *
     * @return string
     */
    public function getWordList()
    {
        if (!file_exists($this->getParameter(self::PARAMETER_DICTIONARY_FILE))) {
            throw new FileNotFoundException();
        }

        return $this->getParameter(self::PARAMETER_DICTIONARY_FILE);
    }

    /**
     * Get word separator.
     *
     * @return string
     */
    public function getWordSeparator()
    {
        return $this->getParameter(self::PARAMETER_WORD_SEPARATOR);
    }

    /**
     * Set word separator.
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

        $this->setParameter(self::PARAMETER_WORD_SEPARATOR, $separator);

        return $this;
    }

    /**
     * Password length
     *
     * @return integer
     */
    public function getLength()
    {
        return $this->getOptionValue(self::OPTION_LENGTH);
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

        $this->setOptionValue(self::OPTION_LENGTH, $characterCount);

        return $this;
    }

    /**
     * @return int|null
     */
    public function getMinPasswordLength()
    {
        if (is_null($this->getLength())) {
            return null;
        }

        $wordCount = $this->getWordCount();

        return ($this->getMinWordLength() * $wordCount) + (strlen($this->getWordSeparator()) * ($wordCount - 1));
    }

    /**
     * @return int|null
     */
    public function getMaxPasswordLength()
    {
        if (is_null($this->getLength())) {
            return null;
        }

        $wordCount = $this->getWordCount();

        return ($this->getMinWordLength() * $wordCount) + (strlen($this->getWordSeparator()) * ($wordCount - 1));
    }
}
