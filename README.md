Password Generator Library
==========================

Simple library for generating random passwords.

[![Build Status](https://travis-ci.org/hackzilla/password-generator.png?branch=master)](https://travis-ci.org/hackzilla/password-generator) 
[![Coverage Status](https://coveralls.io/repos/hackzilla/password-generator/badge.png)](https://coveralls.io/r/hackzilla/password-generator)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/dd072918-d39c-4bd8-bbf0-f9928acee31e/mini.png)](https://insight.sensiolabs.com/projects/dd072918-d39c-4bd8-bbf0-f9928acee31e)

[![Latest Stable Version](https://poser.pugx.org/hackzilla/password-generator/v/stable.svg)](https://packagist.org/packages/hackzilla/password-generator)
[![Total Downloads](https://poser.pugx.org/hackzilla/password-generator/downloads.svg)](https://packagist.org/packages/hackzilla/password-generator)
[![Latest Unstable Version](https://poser.pugx.org/hackzilla/password-generator/v/unstable.svg)](https://packagist.org/packages/hackzilla/password-generator)
[![License](https://poser.pugx.org/hackzilla/password-generator/license.svg)](https://packagist.org/packages/hackzilla/password-generator)


Requirements
------------

* PHP >= 5.3

Installation
------------

Add HackzillaPasswordGenerator in your composer.json:

```json
{
    "require": {
        "hackzilla/password-generator": "~0.1",
    }
}
```

Install Composer

```
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

Now tell composer to download the library by running the command:

``` bash
$ composer update hackzilla/password-generator
```

Composer will install the bundle into your project's `vendor/hackzilla` directory.


Simple Usage
------------

```php
use \Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

$generator = new ComputerPasswordGenerator();
$generator->setOptions(ComputerPasswordGenerator::OPTION_UPPER_CASE | ComputerPasswordGenerator::OPTION_LOWER_CASE | ComputerPasswordGenerator::OPTION_NUMBERS);
$password = $generator->generatePassword();
```


More Passwords Usage
------------

If you want to generate 10 passwords that are 12 characters long.

```php
use \Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

$generator = new ComputerPasswordGenerator();
$generator->setOptions(ComputerPasswordGenerator::OPTION_UPPER_CASE | ComputerPasswordGenerator::OPTION_LOWER_CASE | ComputerPasswordGenerator::OPTION_NUMBERS);
$generator->setLength(12);
$password = $generator->generatePasswords(10);
```

Hybrid Password Generator Usage
-------------------------------

```php
use \Hackzilla\PasswordGenerator\Generator\HybridPasswordGenerator;

$generator = new HybridPasswordGenerator();
$generator->setOptions(HybridPasswordGenerator::OPTION_UPPER_CASE | HybridPasswordGenerator::OPTION_LOWER_CASE | HybridPasswordGenerator::OPTION_NUMBERS);
generator->setSegmentLength(3);
generator->setSegmentCount(4);
generator->setSegmentSeparator('-');

$password = $generator->generatePasswords(10);
```

If you can think of a better name for this password generator then let me know.

The segment separator will be remove from the possible characters.


Example Implementations
-----------------------

* Password Generator Library [https://github.com/hackzilla/password-generator-app]
* Password Generator Bundle [https://github.com/hackzilla/password-generator-bundle]
