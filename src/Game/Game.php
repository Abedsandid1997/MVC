<?php

namespace App\Game;

use App\Game\Player;
use App\Game\Bank;

class Game
{
    /**
     * @var Player Class.
    */
    private object $player;
    /**
     * @var Bank Class.
    */
    private object $bank;

    public function __construct()
    {
        $this->player = new Player();
        $this->bank = new Bank();
    }

    public function getPlayer(): object
    {
        return $this->player;
    }

    public function getBank(): object
    {
        return $this->bank;
    }

    public function getWinner(): bool
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
