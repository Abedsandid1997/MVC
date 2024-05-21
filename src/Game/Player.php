<?php

namespace App\Game;

use App\Game\GraficCard;
use App\Game\DeckOfCards;

class Player
{
    /**
     * @var GraficCard[] Array of GraficCard objects
    */
    private array $hand;

    public function __construct()
    {
        $this->hand = [];
    }
    /**
     * Draw a specified number of cards from the deck.
     *
     * @return GraficCard[] Array of GraficCard objects representing the drawn cards
     */

    public function getHand(): array
    {
        return $this->hand;
    }

    public function addCard(DeckOfCards $deck): void
    {
        $numberOfCard = 1;
        $draw = $deck->draKort($numberOfCard);
        $card = $draw[0];
        $this->hand[] = $card;
    }

    // public function getPoints() {
    //     $this->hand = $card;
    // }

    public function getScore(): int
    {
        $score = 0;
        $aceCount = 0;
    
    // Associativ array för att mappa kortvärden till numeriska värden
    $cardValues = [
        'Kung' => 13,
        'Knekt' => 11,
        'Dam' => 12
    ];

    foreach ($this->hand as $card) {
        $cardValue = $card->getValue();

        if (isset($cardValues[$cardValue])) {
            $score += $cardValues[$cardValue];
        }elseif ($cardValue === 'Ess') {
            $aceCount++;
        } else {
            $score += intval($cardValue);
        }

        
    }

    for ($i = 0; $i < $aceCount; $i++) {
        $aceValue = 1;
        if ($score + 11 <= 21) {
            $aceValue = 11;
        }
        $score += $aceValue;
    }

    return $score;
    }
    public function logic(): bool
    {
        $score = $this->getScore(); // Spara bankens poäng i en variabel

        // Upprepa tills bankens poäng är 17 eller mer
        if ($score > 21) {
            return true;
        }
        return false;
    }


}
