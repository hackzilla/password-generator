<?php

declare(strict_types=1);

namespace Hackzilla\PasswordGenerator\Tests\Model;

use Hackzilla\PasswordGenerator\Model\CharacterSet;

class CharacterSetTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider characterProvider
     *
     * @param mixed $characters
     * @param mixed $result
     */
    public function testConstruct($characters, $result): void
    {
        $characterSet = new CharacterSet($characters);

        $this->assertSame($result, $characterSet->getCharacters());
    }

    public function characterProvider()
    {
        return array(
            array('ABC', 'ABC'),
            array('', ''),
        );
    }

    /**
     * @dataProvider castCharacterProvider
     *
     * @param mixed $characters
     * @param mixed $result
     */
    public function testConstructCast($characters, $result): void
    {
        $characterSet = new CharacterSet($characters);

        $this->assertSame($result, $characterSet->__toString());
        $this->assertSame($result, (string) $characterSet);
    }

    public function castCharacterProvider()
    {
        return array(
            array('ABC', 'ABC'),
            array('', ''),
        );
    }
}
