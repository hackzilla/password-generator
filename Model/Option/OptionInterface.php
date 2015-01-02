<?php

namespace Hackzilla\PasswordGenerator\Model\Option;

interface OptionInterface
{
    public function __construct(array $settings);

    public function getType();

    public function getValue();

    public function setValue($value);
}
