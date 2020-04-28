<?php

declare(strict_types=1);

namespace Hackzilla\PasswordGenerator\Generator;

use Hackzilla\PasswordGenerator\Model\CharacterSet;
use Hackzilla\PasswordGenerator\Model\Option\Option;

class HybridPasswordGenerator extends ComputerPasswordGenerator
{
    const OPTION_SEGMENT_COUNT = 'SEGMENT_COUNT';
    const OPTION_SEGMENT_LENGTH = 'SEGMENT_LENGTH';

    const PARAMETER_SEPARATOR = 'SEPARATOR';

    public function __construct()
    {
        parent::__construct();

        $this
            ->removeOption(self::OPTION_LENGTH)
            ->setOption(self::OPTION_SEGMENT_COUNT, array('type' => Option::TYPE_INTEGER, 'default' => 4))
            ->setOption(self::OPTION_SEGMENT_LENGTH, array('type' => Option::TYPE_INTEGER, 'default' => 3))
            ->setParameter(self::PARAMETER_SEPARATOR, '-')
        ;
    }

    /**
     * Generate character list for us in generating passwords
     * and remove segment separator from character list pool.
     *
     * @return CharacterSet Character list
     *
     * @throws \Exception
     */
    public function getCharacterList() : CharacterSet
    {
        $characterList = parent::getCharacterList();
        $characterList = \str_replace($this->getSegmentSeparator(), '', $characterList);

        return new CharacterSet($characterList);
    }

    /**
     * Generate one password based on options.
     *
     * @return string password
     */
    public function generatePassword() : string
    {
        $characterList = $this->getCharacterList()->getCharacters();
        $characters = \strlen($characterList);
        $password = '';

        $segmentCount = $this->getSegmentCount();
        $segmentLength = $this->getSegmentLength();

        for ($i = 0; $i < $segmentCount; ++$i) {
            if ($password) {
                $password .= $this->getSegmentSeparator();
            }

            for ($j = 0; $j < $segmentLength; ++$j) {
                $password .= $characterList[$this->randomInteger(0, $characters - 1)];
            }
        }

        return $password;
    }

    /**
     * Get number of words in desired password.
     *
     * @return int
     */
    public function getLength() : int
    {
        return $this->getSegmentCount();
    }

    /**
     * Set length of desired password(s).
     *
     * @param int $characterCount
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setLength(int $characterCount) : self
    {
        $this->setSegmentCount($characterCount);

        return $this;
    }

    /**
     * Get number of segments in desired password.
     *
     * @return int
     */
    public function getSegmentCount() : int
    {
        return $this->getOptionValue(self::OPTION_SEGMENT_COUNT);
    }

    /**
     * Set number of segments in desired password(s).
     *
     * @param int $segmentCount
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setSegmentCount(int $segmentCount) : self
    {
        if (!is_int($segmentCount) || $segmentCount < 1) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->setOptionValue(self::OPTION_SEGMENT_COUNT, $segmentCount);

        return $this;
    }

    /**
     * Get number of segments in desired password.
     *
     * @return int
     */
    public function getSegmentLength() : int
    {
        return $this->getOptionValue(self::OPTION_SEGMENT_LENGTH);
    }

    /**
     * Set length of segment.
     *
     * @param int $segmentLength
     *
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setSegmentLength(int $segmentLength) : self
    {
        if (!is_int($segmentLength) || $segmentLength < 1) {
            throw new \InvalidArgumentException('Expected positive integer');
        }

        $this->setOptionValue(self::OPTION_SEGMENT_LENGTH, $segmentLength);

        return $this;
    }

    /**
     * Get Segment Separator.
     *
     * @return string
     */
    public function getSegmentSeparator() : string
    {
        return $this->getParameter(self::PARAMETER_SEPARATOR);
    }

    /**
     * Set segment separator.
     *
     * @param string $segmentSeparator
     *
     * @return \Hackzilla\PasswordGenerator\Generator\HybridPasswordGenerator
     *
     * @throws \InvalidArgumentException
     */
    public function setSegmentSeparator(string $segmentSeparator)
    {
        if (!is_string($segmentSeparator)) {
            throw new \InvalidArgumentException('Expected string');
        }

        $this->setParameter(self::PARAMETER_SEPARATOR, $segmentSeparator);

        return $this;
    }
}
