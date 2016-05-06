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

* PHP >= 5.3.2

Installation
------------

Install Composer

```
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
```

Now tell composer to download the library by running the command:

``` bash
$ composer require hackzilla/password-generator
```

Composer will add the library to your composer.json file and install it into your project's `vendor/hackzilla` directory.


Simple Usage
------------

```php
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

$generator = new ComputerPasswordGenerator();

$generator
  ->setOptionValue(ComputerPasswordGenerator::OPTION_UPPER_CASE, true)
  ->setOptionValue(ComputerPasswordGenerator::OPTION_LOWER_CASE, true)
  ->setOptionValue(ComputerPasswordGenerator::OPTION_NUMBERS, true)
  ->setOptionValue(ComputerPasswordGenerator::OPTION_SYMBOLS, false)
;

$password = $generator->generatePassword();
```


More Passwords Usage
--------------------

If you want to generate 10 passwords that are 12 characters long.

```php
use Hackzilla\PasswordGenerator\Generator\ComputerPasswordGenerator;

$generator = new ComputerPasswordGenerator();

$generator
  ->setUppercase()
  ->setLowercase()
  ->setNumbers()
  ->setSymbols(false)
  ->setLength(12);

$password = $generator->generatePasswords(10);
```

Hybrid Password Generator Usage
-------------------------------

```php
use Hackzilla\PasswordGenerator\Generator\HybridPasswordGenerator;

$generator = new HybridPasswordGenerator();

$generator
  ->setUppercase()
  ->setLowercase()
  ->setNumbers()
  ->setSymbols(false)
  ->setSegmentLength(3)
  ->setSegmentCount(4)
  ->setSegmentSeparator('-');

$password = $generator->generatePasswords(10);
```

If you can think of a better name for this password generator then let me know.

The segment separator will be remove from the possible characters.


Human Password Generator Usage
-------------------------------


```php
use Hackzilla\PasswordGenerator\Generator\HumanPasswordGenerator;

$generator = new HumanPasswordGenerator();

$generator
  ->setWordList('/usr/share/dict/words')
  ->setWordCount(3)
  ->setWordSeparator('-');

$password = $generator->generatePasswords(10);
```


Example Implementations
-----------------------

* Password Generator App [https://github.com/hackzilla/password-generator-app]
* Password Generator Bundle [https://github.com/hackzilla/password-generator-bundle]


Caution
-------

This library uses [mt_rand](http://www.php.net/manual/en/function.mt-rand.php) which is does not generate cryptographically secure values.
Basically an attacker could predict the random passwords this library produces given the right conditions.

If you have a source of randomness you can inject it into the PasswordGenerator, using RandomGeneratorInterface.

PHP 7 has [random_int](http://www.php.net/random_int) function which they say is good to use for cryptographic random integers.

```php
use Hackzilla\PasswordGenerator\Generator\HumanPasswordGenerator;
use Hackzilla\PasswordGenerator\RandomGenerator\Php7RandomGenerator;

$generator = new HumanPasswordGenerator();

$generator
  ->setRandomGenerator(new Php7RandomGenerator())
  ->setWordList('/usr/share/dict/words')
  ->setWordCount(3)
  ->setWordSeparator('-');

$password = $generator->generatePasswords(10);
```
