<?php

namespace App\Game;

use App\Game\Player;
use App\Game\Bank;

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

    /**
     * Constructs a new Game instance with a player and a bank.
     */

    public function __construct()
    {
        $this->player = new Player();
        $this->bank = new Bank();
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
     * @return bool True if the player wins, false otherwise.
     */

    public function isWinner(): bool
    {
        $maxPoints = 21;

        $bankPoints = $this->bank->getScore();
        $playerPoints = $this->player->getScore();
        // Spelaren vinner om banken överskrider maxpoängen
        if ($bankPoints > $maxPoints) {
            return true;
        }
        // Annars vinner spelaren om dess poäng är högre än bankens poäng och inte över maxpoängen
        elseif ($playerPoints > $bankPoints && $playerPoints <= $maxPoints) {
            return true;
        }
        // I alla andra fall vinner banken eller det blir oavgjort
        return false;
    }


}
