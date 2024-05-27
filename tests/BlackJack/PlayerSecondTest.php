<?php

namespace App\BlackJack;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game.
 */
class PlayerSecondTest extends TestCase
{
    public function testBetting(): void
    {
        $player = new Player(2);


        $balance = $player->getBalance();
        $exp = 200;
        $this->assertEquals($exp, $balance);


        $bet = $player->bet(250);
        $balance = $player->getBalance();
        $exp = 200;
        $this->assertFalse($bet); // Bet exceeds balance
        $this->assertEquals($exp, $balance);


        $bet = $player->bet(50);
        $balance = $player->getBalance();
        $exp = 100;
        $this->assertTrue($bet);
        $this->assertEquals($exp, $balance);

        $res = $player->getBetAmount();
        $exp = 50;
        $this->assertEquals($exp, $res);


    }

    public function testWinTokens(): void
    {
        $player = new Player();
        $balance = $player->getBalance();
        $exp = 200;
        $this->assertEquals($exp, $balance);

        $player->bet(50);
        $player->winTokens();

        $res = $player->getBalance();
        $exp = 250;
        $this->assertEquals($exp, $res);

    }

    public function testEqual(): void
    {
        $player = new Player();
        $balance = $player->getBalance();
        $exp = 200;
        $this->assertEquals($exp, $balance);

        $player->bet(50);
        $player->equal();

        $res = $player->getBalance();
        $exp = 200;
        $this->assertEquals($exp, $res);

    }

    public function testGetSetHands(): void
    {
        $player = new Player(3);

        $res = $player->getHands();
        $exp = 3;
        $this->assertCount($exp, $res);

        $player->setNumHands(2);
        $res = $player->getHands();
        $exp = 2;
        $this->assertCount($exp, $res);

    }

    public function testSplitHands(): void
    {
        $card1 = new GraficCard('hearts', 'Kung');
        $card2 = new GraficCard('hearts', 'Dam');
        $card3 = new GraficCard('hearts', '5');

        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card1]);

        $deckStub2 = $this->createMock(DeckOfCards::class);
        $deckStub2->method('draKort')->willReturn([$card2]);

        $deckStub3 = $this->createMock(DeckOfCards::class);
        $deckStub3->method('draKort')->willReturn([$card3]);

        $player = new Player(2);
        $player->addCard($deckStub);
        $res = $player->splitHands();
        $this->assertNotTrue($res);

        $player->addCard($deckStub2);

        $res = $player->splitHands();
        $this->assertTrue($res);
        // Test when its 2 cards and they don't match
        $player->nextHand();

        $player->addCard($deckStub);
        $res = $player->splitHands();
        $this->assertNotTrue($res);

        $player->addCard($deckStub3);

        $res = $player->splitHands();
        $this->assertNotTrue($res);

    }


    public function testSplit(): void
    {
        $card1 = new GraficCard('hearts', 'Kung');
        $card2 = new GraficCard('hearts', 'Dam');

        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card1]);

        $deckStub2 = $this->createMock(DeckOfCards::class);
        $deckStub2->method('draKort')->willReturn([$card2]);


        $player = new Player();
        $player->addCard($deckStub);
        $player->addCard($deckStub2);

        $hands = $player->gethands();
        $exp = 1;
        $this->assertCount($exp, $hands);

        $cards = [$card1 , $card2];
        $res = $player->getHand(0);
        $this->assertNotEmpty($res);
        $this->assertEquals($cards, $res);
        $this->assertCount(2, $res);

        $player->split();
        $hands = $player->gethands();
        $exp = 2;
        $this->assertCount($exp, $hands);

        $cards = [$card1];
        $res = $player->getHand(0);
        $this->assertNotEmpty($res);
        $this->assertEquals($cards, $res);
        $this->assertCount(1, $res);

        $cards = [$card2];
        $res = $player->getHand(1);
        $this->assertNotEmpty($res);
        $this->assertEquals($cards, $res);
        $this->assertCount(1, $res);
    }

}
