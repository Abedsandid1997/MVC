<?php

namespace App\Game;

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
        $this->assertInstanceOf("\App\Game\Player", $player);



    }

    public function testGetHandPlayer(): void
    {
        $card1 = new GraficCard('hearts', '10');
        $card2 = new GraficCard('hearts', '9');

        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card1]);

        $deckStub2 = $this->createMock(DeckOfCards::class);
        $deckStub2->method('draKort')->willReturn([$card2]);

        $player = new Player();
        $res = $player->getHand();
        $this->assertEmpty($res);
        $this->assertCount(0, $res);

        $player->addCard($deckStub);
        $player->addCard($deckStub2);

        $exp = [$card1 , $card2];
        $res = $player->getHand();
        $this->assertNotEmpty($res);
        $this->assertEquals($exp, $res);
        $this->assertCount(2, $res);


    }

    public function testGetScorePlayer(): void
    {
        $card1 = new GraficCard('hearts', 'Dam');
        $card2 = new GraficCard('hearts', 'Ess');
        $card3 = new GraficCard('hearts', 'Kung');
        $card4 = new GraficCard('hearts', 'Knekt');

        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card1]);

        $deckStub2 = $this->createMock(DeckOfCards::class);
        $deckStub2->method('draKort')->willReturn([$card2]);

        $deckStub3 = $this->createMock(DeckOfCards::class);
        $deckStub3->method('draKort')->willReturn([$card3]);

        $deckStub4 = $this->createMock(DeckOfCards::class);
        $deckStub4->method('draKort')->willReturn([$card4]);

        $player = new Player();
        $player->addCard($deckStub);
        $player->addCard($deckStub2);
        $player->addCard($deckStub3);
        $player->addCard($deckStub4);
        $exp = 37;
        $res = $player->getScore();
        $this->assertEquals($exp, $res);



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
        $player->addCard($deckStub);
        $player->addCard($deckStub2);
        $exp = 13;
        $res = $player->getScore();
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

        $res = $player->logic();
        $this->assertTrue($res);



    }
}
