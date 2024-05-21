<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class GraficCard.
 */
class UnicodeCardsTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */

    public function testCreateUnicodeCard(): void
    {


        $card = new UnicodeCards("hearts", "10");
        $this->assertInstanceOf("\App\Game\UnicodeCards", $card);



    }

    public function testGetCard(): void
    {


        $card = new UnicodeCards("hearts", "10");
        $res = $card->getCard();
        $exp = "[10\u{2665}]";
        $this->assertNotEmpty($res);
        $this->assertEquals($res, $exp);



    }


}
