<?php

declare(strict_types=1);

namespace Hackzilla\PasswordGenerator\Model;

class CharacterSet
{
    /** @var string */
    private $characters = '';

    /**
     * CharacterSet constructor.
     *
     * @param string $characters
     */
    public function __construct(string $characters)
    {
        $this->characters = $characters;
    }

    /**
     * Get Character set
     *
     * @return string
     */
    public function getCharacters() : string
    {
        return $this->characters;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->characters;
    }
}
