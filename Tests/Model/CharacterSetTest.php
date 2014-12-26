<?php

namespace Hackzilla\PasswordGenerator\Tests\Model;

use Hackzilla\PasswordGenerator\Model\CharacterSet;

class CharacterSetTest
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
    }

    public function characterProvider()
    {
        return array(
            array('ABC', 'ABC'),
            array('', ''),
            array(null, ''),
        );
    }

}
