<?php

namespace Hackzilla\PasswordGenerator\Model;

class CharacterSet
{
    private $characters;

    public function __construct($characters)
    {
        $this->characters = $characters;
    }

    public function getCharacters()
    {
        return $this->characters;
    }

    public function __toString()
    {
        if (!is_string($this->characters)) {
            return '';
        }

        return $this->characters;
    }
}
 