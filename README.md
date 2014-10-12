Password Generator Library
==========================

Simple library for generating random passwords.

[![Build Status](https://travis-ci.org/hackzilla/password-generator.png?branch=master)](https://travis-ci.org/hackzilla/password-generator)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/dd072918-d39c-4bd8-bbf0-f9928acee31e/big.png)](https://insight.sensiolabs.com/projects/dd072918-d39c-4bd8-bbf0-f9928acee31e)

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

Now tell composer to download the library by running the command:

``` bash
$ php composer.phar update hackzilla/password-generator
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
