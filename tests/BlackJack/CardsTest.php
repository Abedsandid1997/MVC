<?php

namespace App\BlackJack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Cards.
 */
class CardTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */

    public function testCreateCard(): void
    {


        $card = new Cards("hearts", "10");
        $this->assertInstanceOf("\App\BlackJack\Cards", $card);



    }

    public function testGetValueCard(): void
    {


        $card = new Cards("hearts", "10");
        $res = $card->getValue();
        $exp = 10;
        $this->assertNotEmpty($res);
        $this->assertEquals($res, $exp);



    }

    public function testGetSuitCard(): void
    {


        $card = new Cards("hearts", "10");
        $res = $card->getSuit();
        $exp = "hearts";
        $this->assertNotEmpty($res);
        $this->assertEquals($res, $exp);



    }

    public function testGetCard(): void
    {


        $card = new Cards("hearts", "10");
        $res = $card->getCard();
        $exp = "10hearts";
        $this->assertNotEmpty($res);
        $this->assertEquals($res, $exp);



    }

}
