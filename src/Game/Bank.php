<?php

namespace App\Game;

use App\Game\GraficCard;
use App\Game\DeckOfCards;

class Bank
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

    public function addCard(GraficCard $card): void
    {
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
        }
        if (!isset($cardValues[$cardValue]) && $cardValue !== 'Ess') {
            $score += intval($cardValue);
        }

        
    }
    $aceValue = $this->getEssScore($score, $aceCount);
    
    $score += $aceValue;
    return $score;
    }

    public function getEssScore(int $score ,int $aceCount): int
    {
        $aceScore = 0;
        for ($i = 0; $i < $aceCount; $i++) {
            $aceValue = 1;
            if ($score + 11 <= 21) {
                $aceValue = 11;
            }
            $score += $aceValue;
            $aceScore += $aceValue;
            
        }
        return $aceScore;

    }

    public function logic(DeckOfCards $deck): void
    {
        $score = $this->getScore(); // Spara bankens poäng i en variabel

        // Upprepa tills bankens poäng är 17 eller mer
        while ($score < 17) {
            $numberOfCards = 1;
            $draw = $deck->draKort($numberOfCards);
            $card = $draw[0];
            $this->addCard($card);
            $score = $this->getScore(); // Uppdatera bankens poäng för nästa iteration
        }

    }



}
