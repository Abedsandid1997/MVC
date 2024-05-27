<?php

namespace App\BlackJack;

use App\BlackJack\Player;
use App\BlackJack\Bank;
use App\BlackJack\DeckOfCards;

/**
 * Class Game
 *
 * Represents a game of blackjack involving a player and the bank.
 */

class Game
{
    /**
     * The player participating in the game.
     * @var Player Class.
    */
    private object $player;
    /**
     * The bank participating in the game.
     * @var Bank Class.
    */
    private object $bank;

    private int $hands;
    /**
     * Constructs a new Game instance with a player and a bank.
     */

    public function __construct(int $numHands = 1)
    {

        $this->hands = $numHands;
        $this->player = new Player($this->hands);
        $this->bank = new Bank();
    }

    public function changeNumHands(int $numHands): void
    {
        $this->hands = $numHands;
        $this->player->setNumHands($numHands);
    }

    public function changeIntelligenceLevel(string $intelligenceLevel): void
    {

        $this->bank->changeIntelligenceLevel($intelligenceLevel);
    }

    public function getHandsNumber(): int
    {
        return $this->hands;
    }

    public function shareCards(DeckOfCards $deck): void
    {
        for ($i = 0; $i < $this->hands; $i++) {

            $this->player->addCard($deck);
            $this->player->addCard($deck);
            $this->player->nextHand();
        }

        $this->bank->addCard($deck);


    }

    public function playersplit(): void
    {
        $this->hands += 1 ;
        $this->player->split();
    }


    /**
     * Retrieves the player object participating in the game.
     *
     * @return Player The player participating in the game.
     */
    public function getPlayer(): object
    {
        return $this->player;
    }

    /**
     * Retrieves the bank object participating in the game.
     *
     * @return Bank The bank participating in the game.
     */
    public function getBank(): object
    {
        return $this->bank;
    }
    /**
     * Determines the winner of the game based on player and bank scores.
     *
     * The player wins if the bank exceeds the maximum points, or if the player's
     * points are higher than the bank's points without exceeding the maximum points.
     * In all other cases, the bank wins or the game is a draw.
     *
     * @return array<string> Array mapping winner
     */

    public function isWinner(): array
    {
        $maxPoints = 21;
        $winners = [];
        $bankPoints = $this->bank->getScore();
        $this->bank->resetHand();
        for ($i = 0; $i < $this->hands; $i++) {
            $playerPoints = $this->player->getScore($i);
            // Spelaren vinner om banken överskrider maxpoängen
            if ($playerPoints > $maxPoints) {
                $winners[$i] = "Loss";
            } elseif ($bankPoints > $maxPoints) {
                $winners[$i] = "Wins";
                $this->player->winTokens();
            } elseif ($playerPoints > $bankPoints) {
                $winners[$i] = "Wins";
                $this->player->winTokens();
            } elseif ($playerPoints === $bankPoints) {
                $winners[$i] = "Equal";
                $this->player->equal();

            }
            // I alla andra fall vinner banken
            elseif ($playerPoints < $bankPoints) {
                $winners[$i] = "Loss";
            }
        }
        return $winners;
    }


}
