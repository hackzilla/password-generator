<?php

namespace Hackzilla\PasswordGenerator\Generator;

interface PasswordGeneratorInterface
{
    public function getPossibleOptions();

    public function setOptions($options);
    
    public function setParameter($parameter, $value);

    public function setLength($characterCount);

    public function generatePasswords($count = 1);

    public function generatePassword();
}
