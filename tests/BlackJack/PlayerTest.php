<?php

namespace App\BlackJack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game.
 */
class PlayerTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */

    public function testCreatePlayer(): void
    {


        $player = new Player();
        $this->assertInstanceOf("\App\BlackJack\Player", $player);



    }

    public function testGetHandPlayer(): void
    {
        $card1 = new GraficCard('hearts', '10');
        $card2 = new GraficCard('hearts', '9');

        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card1]);

        $deckStub2 = $this->createMock(DeckOfCards::class);
        $deckStub2->method('draKort')->willReturn([$card2]);

        $player = new Player(2);
        $hand1 = $player->getHand(0);
        $this->assertEmpty($hand1);
        $this->assertCount(0, $hand1);

        $hand2 = $player->getHand(1);
        $this->assertEmpty($hand2);
        $this->assertCount(0, $hand2);

        $player->addCard($deckStub);
        $player->addCard($deckStub2);

        $exp = [$card1 , $card2];
        $res = $player->getHand(0);
        $this->assertNotEmpty($res);
        $this->assertEquals($exp, $res);
        $this->assertCount(2, $res);

        $exp = [];
        $res = $player->getHand(1);
        $this->assertEmpty($res);
        $this->assertEquals($exp, $res);
        $this->assertCount(0, $res);

    }
    public function testBalance(): void
    {
        $player = new Player();
        $res = $player->getBalance();
        $exp = 200;
        $this->assertEquals($exp, $res);
    }

    public function testHands(): void
    {
        $player = new Player(2);

        $res = $player->getactiveHand();
        $exp = 0;
        $this->assertEquals($exp, $res);
        // move to next hand
        $player->nextHand();
        $res = $player->getactiveHand();
        $exp = 1;
        $this->assertEquals($exp, $res);

        // move to next hand
        $player->nextHand();
        $res = $player->getactiveHand();
        $exp = 0;
        $this->assertEquals($exp, $res);
    }


    public function testLogicPlayer(): void
    {
        $card1 = new GraficCard('hearts', 'Kung');
        $card2 = new GraficCard('hearts', 'Dam');

        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card1]);

        $deckStub2 = $this->createMock(DeckOfCards::class);
        $deckStub2->method('draKort')->willReturn([$card2]);


        $player = new Player();
        $player->addCard($deckStub);
        $res = $player->logic();
        $this->assertNotTrue($res);

        $player->addCard($deckStub2);
        $player->addCard($deckStub2);

        $res = $player->logic();
        $this->assertTrue($res);



    }


    public function testGetScorePlayer(): void
    {
        $card1 = new GraficCard('hearts', 'Dam');
        $card2 = new GraficCard('hearts', 'Ess');
        $card3 = new GraficCard('hearts', 'Kung');
        $card4 = new GraficCard('hearts', '9');

        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card1]);

        $deckStub2 = $this->createMock(DeckOfCards::class);
        $deckStub2->method('draKort')->willReturn([$card2]);

        $deckStub3 = $this->createMock(DeckOfCards::class);
        $deckStub3->method('draKort')->willReturn([$card3]);

        $deckStub4 = $this->createMock(DeckOfCards::class);
        $deckStub4->method('draKort')->willReturn([$card4]);

        $player = new Player();
        $handIndex = 0;

        $player->addCard($deckStub);
        $player->addCard($deckStub2);
        $player->addCard($deckStub3);
        $exp = 21;
        $res = $player->getScore($handIndex);
        $this->assertEquals($exp, $res);

        $player->addCard($deckStub4);
        $exp = 30;
        $res = $player->getScore($handIndex);
        $this->assertEquals($exp, $res);
        $this->assertIsInt($exp);




    }

    public function testGetScoreEssPlayer(): void
    {
        $card1 = new GraficCard('hearts', '2');
        $card2 = new GraficCard('hearts', 'Ess');

        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card1]);

        $deckStub2 = $this->createMock(DeckOfCards::class);
        $deckStub2->method('draKort')->willReturn([$card2]);

        $player = new Player();
        $handIndex = 0;

        $player->addCard($deckStub);
        $player->addCard($deckStub2);
        $exp = 13;
        $res = $player->getScore($handIndex);
        $this->assertEquals($exp, $res);



    }


}
