<?php

namespace App\BlackJack;

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
        $this->assertInstanceOf("\App\BlackJack\DeckOfCards", $deck);



    }

    public function testGetCards(): void
    {


        $deck = new DeckOfCards();
        $res = $deck->getCards();
        $exp = 52 * 6;
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

        $exp = 52 * 6;
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

        $this->assertCount(52 * 6, $cards);
        $numberOfCardsToDraw = 5;
        $drawnCards = $deck->draKort($numberOfCardsToDraw);
        $cardsAfterDraw = $deck->getCards();
        $cardsAntal = 52 * 6 - 5;
        $this->assertCount($numberOfCardsToDraw, $drawnCards);

        $this->assertCount($cardsAntal, $cardsAfterDraw);
    }
    /**
     * @SuppressWarnings(PHPMD)
     */
    public function testGetStatistics(): void
    {

        $deck = new DeckOfCards();


        $drawnCards = [];
        for($i = 0 ; $i < 5;$i++) {
            $card = $deck->draKort(1);
            $drawnCards[] = $card[0];
        }
        $expectedStatistics = [];
        foreach ($drawnCards as $card) {
            $value = $card->getValue();
            if (isset($expectedStatistics[$value])) {
                $expectedStatistics[$value]++;
            } else {
                $expectedStatistics[$value] = 1;
            }
        }

        $actualStatistics = $deck->getStatistics();

        $this->assertEquals($expectedStatistics, $actualStatistics);
        $this->assertNotEmpty($actualStatistics);

    }

    public function testGetAdviceHigh(): void
    {

        $deck = new DeckOfCards();
        $counterStub = $this->createMock(Counter::class);
        $counterStub->method('getRunningCount')->willReturn(10);
        $deck->setCounter($counterStub);

        $res = $deck->getAdvice();
        $exp = "Higher Bet";

        $this->assertEquals($exp, $res);
        $this->assertIsString($exp);



    }

    public function testGetAdviceLow(): void
    {

        $deck = new DeckOfCards();
        $counterStub = $this->createMock(Counter::class);
        $counterStub->method('getRunningCount')->willReturn(-10);
        $deck->setCounter($counterStub);

        $res = $deck->getAdvice() ;
        $exp = "Lower Bet" ;

        $this->assertEquals($exp, $res);
        $this->assertIsString($exp);



    }

    public function testGetAdviceMainten(): void
    {

        $deck = new DeckOfCards();
        $counterStub = $this->createMock(Counter::class);
        $counterStub->method('getRunningCount')->willReturn(0);
        $deck->setCounter($counterStub);

        $res = $deck->getAdvice();
        $exp = "Maintain Bet";

        $this->assertEquals($exp, $res);
        $this->assertIsString($exp);

    }

    public function testProbabilityOfBusting(): void
    {

        $deck = new DeckOfCards();
        $counterStub = $this->createMock(Counter::class);
        $counterStub->method('probabilityOfBusting')->willReturn(50.3);
        $deck->setCounter($counterStub);

        $fakeHandValue = 10;
        $res = $deck->probabilityOfBusting($fakeHandValue);
        $exp = 50.3 ;

        $this->assertEquals($exp, $res);
        $this->assertIsFloat($exp);

    }

}
