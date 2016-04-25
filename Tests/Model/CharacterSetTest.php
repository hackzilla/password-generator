<?php

namespace Hackzilla\PasswordGenerator\Tests\Model;

use Hackzilla\PasswordGenerator\Model\CharacterSet;

class CharacterSetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider characterProvider
     * @param $characters
     * @param $result
     */
    public function testConstruct($characters, $result)
    {
        $characterSet = new CharacterSet($characters);

        $this->assertEquals($result, $characterSet->getCharacters());
        $this->assertEquals($result, $characterSet->__toString());
    }

    public function characterProvider()
    {
        return array(
            array('ABC', 'ABC'),
            array('', ''),
            array(null, ''),
        );
    }

    /**
     * @dataProvider castCharacterProvider
     * @param $characters
     * @param $result
     */
    public function testConstructCast($characters, $result)
    {
        $characterSet = new CharacterSet($characters);

        $this->assertEquals($result, (string) $characterSet);
    }

    public function castCharacterProvider()
    {
        return array(
            array('ABC', 'ABC'),
            array('', ''),
            array(null, null),
        );
    }
}
