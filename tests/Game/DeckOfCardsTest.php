<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;


/**
 * Test cases for class GraficCard.
 */
class DeckOfCardsTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */

    public function testCreateDeckOfCard(): void
    {


        $deck = new DeckOfCards();
        $this->assertInstanceOf("\App\Game\DeckOfCards", $deck);



    }

    public function testGetCards(): void
    {


        $deck = new DeckOfCards();
        $res = $deck->getCards();
        $exp = 52;
        $this->assertCount($exp, $res);
        // $this->assertEquals($res,$exp);

        $this->assertIsArray($res);

    }

    public function testShuffleCards(): void
    {


        $deck = new DeckOfCards();
        $deckBeforeShuffle = $deck->getCards();
        $deck->shuffle();
        $deckAfterShuffle = $deck->getCards();

        $exp = 52;
        $this->assertCount($exp, $deckBeforeShuffle);
        $this->assertCount($exp, $deckAfterShuffle);
        // $this->assertEquals($res,$exp);

        $this->assertNotEquals($deckAfterShuffle, $deckBeforeShuffle);

    }
    public function testCountCards(): void
    {


        $deck = new DeckOfCards();
        $antal = $deck->count();
        $cards = $deck->getCards();

        $this->assertCount($antal, $cards);


    }

    public function testDrawCards(): void
    {


        $deck = new DeckOfCards();
        $cards = $deck->getCards();

        $this->assertCount(52, $cards);
        $numberOfCardsToDraw = 5;
        $drawnCards = $deck->draKort($numberOfCardsToDraw);
        $cardsAfterDraw = $deck->getCards();
        $cardsAntal = 52 - 5;
        $this->assertCount($numberOfCardsToDraw, $drawnCards);

        $this->assertCount($cardsAntal, $cardsAfterDraw);
    }

}
