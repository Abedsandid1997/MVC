<?php

namespace App\BlackJack;

use PHPUnit\Framework\TestCase;

use App\BlackJack\Player;
use App\BlackJack\Bank;

/**
 * Test cases for class Game.
 */
class GameTest extends TestCase
{
    /**
     * Construct object and verify that the object has the expected
     * properties, use no arguments.
     */



    public function testCreateGame(): void
    {
        $game = new Game();
        $this->assertInstanceOf("\App\BlackJack\Game", $game);

        $player = $game->getPlayer();
        $this->assertInstanceOf("\App\BlackJack\Player", $player);

        $bank = $game->getBank();
        $this->assertInstanceOf("\App\BlackJack\Bank", $bank);



    }

    public function testChangeNumHands(): void
    {
        $game = new Game();


        $hands = $game->getHandsNumber();
        $exp = 1;
        $this->assertEquals($exp, $hands);
        $this->assertIsInt($hands);
        $game->changeNumHands(2);

        $hands = $game->getHandsNumber();
        $exp = 2;
        $this->assertEquals($exp, $hands);

    }

    public function testChangeIntelligenceLevel(): void
    {
        $game = new Game();

        $bank = $game->getBank();

        $intelligenceLevel = $bank->getIntelligenceLevel();
        $exp = 'basic';
        $this->assertEquals($exp, $intelligenceLevel);
        $this->assertIsString($intelligenceLevel);

        $game->changeIntelligenceLevel("advanced");
        $intelligenceLevel = $bank->getIntelligenceLevel();
        $exp = 'advanced';
        $this->assertEquals($exp, $intelligenceLevel);

    }

    public function testShareCards(): void
    {
        $game = new Game();

        $deck = new DeckOfCards();

        $bank = $game->getBank();
        $bankHand = $bank->getHand();
        $exp = 0;
        $this->assertCount($exp, $bankHand);

        $player = $game->getPlayer();
        $playerHand = $player->getHand(0);
        $exp = 0;
        $this->assertCount($exp, $playerHand);


        $game->shareCards($deck);

        $bankHand = $bank->getHand();
        $exp = 1;
        $this->assertCount($exp, $bankHand);

        $playerHand = $player->getHand(0);
        $exp = 2;
        $this->assertCount($exp, $playerHand);

    }



    public function testPlayerSplit(): void
    {

        $card1 = new GraficCard('hearts', 'Kung');
        $card2 = new GraficCard('hearts', 'Dam');

        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card1]);

        $deckStub2 = $this->createMock(DeckOfCards::class);
        $deckStub2->method('draKort')->willReturn([$card2]);


        $game = new Game();
        $player = $game->getPlayer();

        $hands = $game->getHandsNumber();
        $exp = 1;
        $this->assertEquals($exp, $hands);




        $player->addCard($deckStub);
        $player->addCard($deckStub2);

        $game->playersplit();

        $hands = $game->getHandsNumber();
        $exp = 2;
        $this->assertEquals($exp, $hands);


    }



    public function testWinners(): void
    {

        $card = new GraficCard('hearts', 'Kung');

        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card]);


        $game = new Game();
        $player = $game->getPlayer();

        $player->addCard($deckStub);
        $player->addCard($deckStub);


        $winner = $game->isWinner();
        $exp = ["Wins"];
        $this->assertEquals($exp, $winner);

        $player->addCard($deckStub);


        $winner = $game->isWinner();
        $exp = ["Loss"];
        $this->assertEquals($exp, $winner);

    }

    public function testWinners2(): void
    {

        $card = new GraficCard('hearts', 'Kung');

        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card]);


        $game = new Game();
        $bank = $game->getBank();

        $bank->addCard($deckStub);
        $bank->addCard($deckStub);
        $bank->addCard($deckStub);


        $winner = $game->isWinner();
        $exp = ["Wins"];
        $this->assertEquals($exp, $winner);


        $game = new Game();
        $bank = $game->getBank();

        $bank->addCard($deckStub);
        $bank->addCard($deckStub);

        $winner = $game->isWinner();
        $exp = ["Loss"];
        $this->assertEquals($exp, $winner);


    }


    public function testDrawGame(): void
    {

        $card = new GraficCard('hearts', 'Kung');

        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card]);


        $game = new Game();
        $bank = $game->getBank();
        $player = $game->getPlayer();

        $player->addCard($deckStub);
        $player->addCard($deckStub);

        $bank->addCard($deckStub);
        $bank->addCard($deckStub);

        $winner = $game->isWinner();
        $exp = ["Equal"];
        $this->assertEquals($exp, $winner);
    }


}
