<?php

namespace Hackzilla\PasswordGenerator\Tests\Generator;

use Hackzilla\PasswordGenerator\Generator\HumanPasswordGenerator;

class HumanPasswordGeneratorTest extends \PHPUnit_Framework_TestCase
{

    private $_object;

    public function setup()
    {
        $this->_object = new HumanPasswordGenerator();
        $this->_object->setWordSeparator('');
    }

    public function testLength()
    {
        $this->_object->setLength(1);
        $this->assertEquals($this->_object->getLength(), 1);

        $this->_object->setLength(6);
        $this->assertEquals($this->_object->getLength(), 6);

        $this->setExpectedException('InvalidArgumentException');
        $this->_object->setLength(-6);

        $this->setExpectedException('InvalidArgumentException');
        $this->_object->setLength('fail');
    }

    public function testMinWordLength()
    {
        $this->_object->setMinWordLength(1);
        $this->assertEquals($this->_object->getMinWordLength(), 1);

        $this->_object->setMinWordLength(6);
        $this->assertEquals($this->_object->getMinWordLength(), 6);

        $this->setExpectedException('InvalidArgumentException');
        $this->_object->setMinWordLength(-6);

        $this->setExpectedException('InvalidArgumentException');
        $this->_object->setMinWordLength('fail');
    }

    public function testMaxWordLength()
    {
        $this->_object->setMaxWordLength(1);
        $this->assertEquals($this->_object->getMaxWordLength(), 1);

        $this->_object->setMaxWordLength(6);
        $this->assertEquals($this->_object->getMaxWordLength(), 6);

        $this->setExpectedException('InvalidArgumentException');
        $this->_object->setMaxWordLength(-6);

        $this->setExpectedException('InvalidArgumentException');
        $this->_object->setMaxWordLength('fail');
    }

    public function testWordSeparator()
    {
        $this->_object->setWordSeparator('');
        $this->assertEquals($this->_object->getWordSeparator(), '');

        $this->_object->setWordSeparator('-');
        $this->assertEquals($this->_object->getWordSeparator(), '-');

        $this->_object->setWordSeparator('-?*');
        $this->assertEquals($this->_object->getWordSeparator(), '-?*');

        $this->setExpectedException('InvalidArgumentException');
        $this->_object->setWordSeparator(-6);

        $this->setExpectedException('InvalidArgumentException');
        $this->_object->setMaxWordLength(null);
    }

    public function getSimpleWordList()
    {
        $reflClass = new \ReflectionClass(get_class($this));
        $filename = dirname($reflClass->getFileName()) . DIRECTORY_SEPARATOR . 'Data' . DIRECTORY_SEPARATOR . 'WordList' . DIRECTORY_SEPARATOR . 'simple.txt';

        return $filename;
    }

    public function testWordList()
    {
        $filename = $this->getSimpleWordList();

        $this->_object->setWordList($filename);
        $this->assertEquals($this->_object->getWordList(), $filename);

        $this->setExpectedException('InvalidArgumentException');
        $this->_object->setWordList(7);

        $this->setExpectedException('FileNotFoundException');
        $this->_object->setWordList('fail');
    }

    public function testGenerateWordList()
    {
        $filename = $this->getSimpleWordList();

        $this->_object->setWordList($filename);
        $words = $this->_object->generateWordList();

        $this->assertEquals(count($words), 7);
        $this->assertEquals($words[0], 'blancmange');
    }

    /**
     * @dataProvider lengthProvider
     */
    public function testGeneratePasswords($length)
    {
        $filename = $this->getSimpleWordList();

        $this->_object->setWordList($filename);
        $this->_object->setLength($length);
        $passwords = $this->_object->generatePasswords($length);

        $this->assertEquals(count($passwords), $length);

        foreach ($passwords as $password) {
            $this->assertEquals($password, \str_repeat('blancmange', $length));
        }
    }

    /**
     * @dataProvider lengthProvider
     */
    public function testGeneratePassword($length)
    {
        $filename = $this->getSimpleWordList();

        $this->_object->setWordList($filename);
        $this->_object->setLength($length);
        $this->assertEquals($this->_object->generatePassword(), \str_repeat('blancmange', $length));
    }

    /**
     * @dataProvider lengthProvider
     */
    public function testGeneratePasswordWithSeparator($length)
    {
        $filename = $this->getSimpleWordList();

        $this->_object->setWordList($filename);
        $this->_object->setLength($length);
        $this->_object->setWordSeparator('-');
        $this->assertEquals($this->_object->generatePassword(), $this->makePassword('blancmange', $length, '-'));
    }

    private function makePassword($word, $length, $separator)
    {
        $password = '';

        for ($i = 0; $i < $length; $i++) {
            if ($i) {
                $password .= $separator;
            }

            $password .= $word;
        }

        return $password;
    }

    public function lengthProvider()
    {
        return array(
            array(1),
            array(4),
            array(8),
            array(16),
        );
    }

}
