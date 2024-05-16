<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;

use App\Game\Player;
use App\Game\Bank;

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
        $this->assertInstanceOf("\App\Game\Game", $game);

        $player = $game->getPlayer();
        $this->assertInstanceOf("\App\Game\Player", $player);

        $bank = $game->getBank();
        $this->assertInstanceOf("\App\Game\Bank", $bank);



    }

    public function testPlayerWinGame(): void
    {


        $fakeCard = new GraficCard('hearts', '10');
        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$fakeCard]);

        $game = new Game();
        $this->assertInstanceOf("\App\Game\Game", $game);

        /** @var Player $player */
        $player = $game->getPlayer();
        $player->addCard($deckStub);
        $player->addCard($deckStub);
        $playerScore = $player->getScore();
        // $exp= true;
        $res = $game->isWinner();
        $expScore = 20;

        $this->assertTrue($res);
        $this->assertEquals($playerScore, $expScore);


    }

    public function testBankWinGame(): void
    {

        $fakeCard = new GraficCard('hearts', '10');


        $game = new Game();
        $this->assertInstanceOf("\App\Game\Game", $game);



        /** @var Bank $bank */

        $bank = $game->getBank();
        $bank->addCard($fakeCard);
        $bank->addCard($fakeCard);
        $bankScore = $bank->getScore();
        // $exp= false;
        $res = $game->isWinner();
        $expScore = 20;

        $this->assertNotTrue($res);
        $this->assertEquals($bankScore, $expScore);


    }

    public function testBankLoseGame(): void
    {

        $fakeCard = new GraficCard('hearts', '10');


        $game = new Game();
        $this->assertInstanceOf("\App\Game\Game", $game);

        /** @var Bank $bank */
        $bank = $game->getBank();
        $bank->addCard($fakeCard);
        $bank->addCard($fakeCard);
        $bank->addCard($fakeCard);
        $bankScore = $bank->getScore();

        $res = $game->isWinner();
        $expScore = 30;

        $this->assertTrue($res);
        $this->assertEquals($bankScore, $expScore);


    }

    public function testDrawGame(): void
    {

        $fakeCard = new GraficCard('hearts', '10');
        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$fakeCard]);

        $game = new Game();

        /** @var Player $player */
        $player = $game->getPlayer();
        $player->addCard($deckStub);
        $player->addCard($deckStub);
        $playerScore = $player->getScore();

        /** @var Bank $bank */
        $bank = $game->getBank();
        $bank->addCard($fakeCard);
        $bank->addCard($fakeCard);
        $bankScore = $bank->getScore();

        $res = $game->isWinner();
        $expScore = 20;

        $this->assertNotTrue($res);
        $this->assertEquals($bankScore, $expScore);
        $this->assertEquals($playerScore, $expScore);


    }
}
