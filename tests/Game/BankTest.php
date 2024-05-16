<?php

namespace App\Game;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class Game.
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
        $this->assertInstanceOf("\App\Game\Bank", $bank);



    }

    public function testGetHandBank(): void
    {
        $card1 = new GraficCard('hearts', 'Kung');
        $card2 = new GraficCard('hearts', 'Knekt');


        $bank = new Bank();

        $res = $bank->getHand();
        $this->assertCount(0, $res);
        $this->assertEmpty($res);

        $bank->addCard($card1);
        $bank->addCard($card2);
        $exp = [$card1 , $card2];
        $res = $bank->getHand();
        $this->assertEquals($exp, $res);
        $this->assertCount(2, $res);
        $this->assertNotEmpty($res);



    }

    public function testGetScoreBank(): void
    {
        $card1 = new GraficCard('hearts', 'Dam');
        $card2 = new GraficCard('hearts', 'Ess');
        $card3 = new GraficCard('hearts', 'Kung');
        $card4 = new GraficCard('hearts', 'Knekt');


        $bank = new Bank();
        $bank->addCard($card1);
        $bank->addCard($card2);
        $bank->addCard($card3);
        $bank->addCard($card4);
        $exp = 37;
        $res = $bank->getScore();
        $this->assertEquals($exp, $res);



    }

    public function testGetScoreEssBank(): void
    {
        $card1 = new GraficCard('hearts', '2');
        $card2 = new GraficCard('hearts', 'Ess');



        $bank = new Bank();
        $bank->addCard($card1);
        $bank->addCard($card2);
        $exp = 13;
        $res = $bank->getScore();
        $this->assertEquals($exp, $res);



    }



    public function testLogicBank(): void
    {
        $card = new GraficCard('hearts', '10');
        $deckStub = $this->createMock(DeckOfCards::class);
        $deckStub->method('draKort')->willReturn([$card]);



        $bank = new Bank();
        $bank->logic($deckStub);
        $exp = 20;
        $res = $bank->getScore();
        $this->assertEquals($exp, $res);



    }
}
