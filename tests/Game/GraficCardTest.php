<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class GraficCard.
 */
class GraficCardTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */

    public function testCreateCard(): void
    {


        $card = new GraficCard("hearts", "10");
        $this->assertInstanceOf("\App\Game\GraficCard", $card);



    }

    public function testGetCard(): void
    {


        $card = new GraficCard("hearts", "10");
        $res = $card->getCard();
        $exp = "img/10hearts.png";
        $this->assertNotEmpty($res);
        $this->assertEquals($res, $exp);



    }


}
