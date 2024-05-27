<?php

namespace App\BlackJack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class BlackJack.
 */
class BankTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */

    public function testCreateBank(): void
    {


        $bank = new Bank();
        $this->assertInstanceOf("\App\BlackJack\Bank", $bank);



    }

    public function testGetHandBank(): void
    {
        $card1 = new GraficCard('hearts', '10');
        $card2 = new GraficCard('hearts', '9');

        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card1]);

        $deckStub2 = $this->createMock(DeckOfCards::class);
        $deckStub2->method('draKort')->willReturn([$card2]);

        $bank = new Bank();

        $res = $bank->getHand();
        $this->assertCount(0, $res);
        $this->assertEmpty($res);

        $bank->addCard($deckStub);
        $bank->addCard($deckStub2);
        $exp = [$card1 , $card2];
        $res = $bank->getHand();
        $this->assertEquals($exp, $res);
        $this->assertCount(2, $res);
        $this->assertNotEmpty($res);



    }

    public function testGetScoreBank(): void
    {
        $card1 = new GraficCard('hearts', '9');
        $card2 = new GraficCard('hearts', 'Kung');

        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card1]);

        $deckStub2 = $this->createMock(DeckOfCards::class);
        $deckStub2->method('draKort')->willReturn([$card2]);

        $bank = new Bank();

        $res = $bank->getHand();
        $this->assertCount(0, $res);
        $this->assertEmpty($res);

        $bank->addCard($deckStub);
        $bank->addCard($deckStub2);
        $exp = 19;
        $res = $bank->getScore();
        $this->assertEquals($exp, $res);



    }

    public function testGetScoreEssBank(): void
    {
        $card1 = new GraficCard('hearts', '2');
        $card2 = new GraficCard('hearts', 'Ess');



        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card1]);

        $deckStub2 = $this->createMock(DeckOfCards::class);
        $deckStub2->method('draKort')->willReturn([$card2]);

        $bank = new Bank();

        $res = $bank->getHand();
        $this->assertCount(0, $res);
        $this->assertEmpty($res);

        $bank->addCard($deckStub);
        $bank->addCard($deckStub2);

        $exp = 13;
        $res = $bank->getScore();
        $this->assertEquals($exp, $res);



    }


    public function testResetHand(): void
    {
        $deck = new DeckOfCards();

        $bank = new Bank();

        $res = $bank->getHand();
        $this->assertCount(0, $res);
        $this->assertEmpty($res);

        $bank->addCard($deck);
        $bank->addCard($deck);

        $res = $bank->getHand();
        $this->assertCount(2, $res);
        $this->assertNotEmpty($res);

        $bank->resetHand();
        $res = $bank->getHand();
        $this->assertCount(0, $res);
        $this->assertEmpty($res);


    }

    public function testStrategies(): void
    {
        $card = new GraficCard('hearts', '9');





        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card]);

        $bank = new Bank('basic');

        $bank->play($deckStub);

        $res = $bank->getScore();
        $exp = 18;
        $this->assertEquals($exp, $res);

        // change strategy
        $bank->changeIntelligenceLevel("easy");
        $bank->play($deckStub);
        $res = $bank->getScore();
        $exp = 27;
        $this->assertEquals($exp, $res);

    }

    public function testAdvancedStrategy(): void
    {
        $card = new GraficCard('hearts', '6');



        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card]);
        $deckStub->method('probabilityOfBusting')->willReturn(30.0);


        $bank = new Bank('advanced');

        $bank->addCard($deckStub);
        $bank->addCard($deckStub);
        $bank->addCard($deckStub);

        $this->assertTrue($bank->shouldHit($deckStub));
        // Låt banken spela med avancerad strategi
        $bank->play($deckStub);

        $res = $bank->getScore();
        $exp = 24;
        $this->assertEquals($exp, $res);



    }

    public function testAdvancedStrategy2(): void
    {
        $card = new GraficCard('hearts', '6');



        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card]);
        $deckStub->method('probabilityOfBusting')->willReturn(90.0);


        $bank = new Bank('advanced');

        $bank->addCard($deckStub);
        $bank->addCard($deckStub);
        $bank->addCard($deckStub);

        $this->assertFalse($bank->shouldHit($deckStub));

        $bank->play($deckStub);

        $res = $bank->getScore();
        $exp = 18;
        $this->assertEquals($exp, $res);



    }

}
