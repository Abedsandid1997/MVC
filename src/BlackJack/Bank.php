<?php

namespace App\BlackJack;

use App\BlackJack\GraficCard;
use App\BlackJack\DeckOfCards;
use App\BlackJack\Counter;

class Bank
{
    /**
     * @var GraficCard[] Array of GraficCard objects
    */
    private array $hand;
    private string $intelligenceLevel;
    public function __construct(string $intelligenceLevel = 'basic')
    {
        $this->hand = [];
        $this->intelligenceLevel = $intelligenceLevel;
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

    public function resetHand(): void
    {
        $this->hand = [];
    }

    public function getIntelligenceLevel(): string
    {
        return $this->intelligenceLevel;
    }
    public function changeIntelligenceLevel(string $intelligenceLevel): void
    {
        $this->intelligenceLevel = $intelligenceLevel;
    }

    public function play(DeckOfCards $deck): void
    {
        switch ($this->intelligenceLevel) {
            case 'basic':
                $this->basicStrategy($deck);
                break;
            case 'advanced':
                $this->advancedStrategy($deck);
                break;
            case 'easy':
                $this->easyStrategy($deck);
                break;
        }
    }

    private function advancedStrategy(DeckOfCards $deck): void
    {
        while ($this->shouldHit($deck)) {
            $this->addCard($deck);
        }
    }
    public function shouldHit(DeckOfCards $deck): bool
    {
        $currentScore = $this->getScore();
        $bustProbability = $deck->probabilityOfBusting($currentScore);
        $bustProbability /= 100;

        return $currentScore < 17 || ($currentScore < 20 && $bustProbability < 0.5);
    }

    public function addCard(DeckOfCards $deck): void
    {

        $numberOfCards = 1;
        $draw = $deck->draKort($numberOfCards);
        $card = $draw[0];
        $this->hand[] = $card;
    }


    public function getScore(): int
    {
        $score = 0;
        $aceCount = 0;

        // Associativ array för att mappa kortvärden till numeriska värden
        $cardValues = [
            'Kung' => 10,
            'Knekt' => 10,
            'Dam' => 10
        ];

        foreach ($this->hand as $card) {
            $cardValue = $card->getValue();

            if (isset($cardValues[$cardValue])) {
                $score += $cardValues[$cardValue];
            } elseif ($cardValue === 'Ess') {
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

    public function getEssScore(int $score, int $aceCount): int
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

    public function basicStrategy(DeckOfCards $deck): void
    {
        $score = $this->getScore(); // Spara bankens poäng i en variabel

        // Upprepa tills bankens poäng är 17 eller mer
        while ($score < 17) {

            $this->addCard($deck);
            $score = $this->getScore(); // Uppdatera bankens poäng för nästa iteration
        }

    }
    public function easyStrategy(DeckOfCards $deck): void
    {
        $score = $this->getScore(); // Spara bankens poäng i en variabel

        // Upprepa tills bankens poäng är 17 eller mer
        while ($score < 20) {

            $this->addCard($deck);
            $score = $this->getScore(); // Uppdatera bankens poäng för nästa iteration
        }

    }



}
