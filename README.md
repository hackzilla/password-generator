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
$generator = new \Hackzilla\PasswordGenerator\Generator\PasswordGenerator();
$generator->setOptions(self::OPTION_UPPER_CASE | self::OPTION_LOWER_CASE | self::OPTION_NUMBERS);
$password = $generator->generatePassword();
```


More Passwords Usage
------------

If you want to generate 10 passwords that are 12 characters long.

```php
$generator = new \Hackzilla\PasswordGenerator\Generator\PasswordGenerator();
$generator->setOptions(self::OPTION_UPPER_CASE | self::OPTION_LOWER_CASE | self::OPTION_NUMBERS);
$generator->setLength(12);
$password = $generator->generatePasswords(10);
```
