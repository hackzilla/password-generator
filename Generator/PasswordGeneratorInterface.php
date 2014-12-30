<?php

namespace Hackzilla\PasswordGenerator\Generator;

interface PasswordGeneratorInterface
{
    public function getPossibleOptions();

    public function setOption($option, $optionSettings);

    public function getOption($option);

    public function setOptionValue($option, $value);

    public function getOptionValue($option);

    public function generatePasswords($count = 1);

    public function generatePassword();
}
