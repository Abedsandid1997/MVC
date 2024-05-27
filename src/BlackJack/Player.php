<?php

namespace App\BlackJack;

use App\BlackJack\GraficCard;
use App\BlackJack\DeckOfCards;

class Player
{
    /**
     * @var array<int, array<int, GraficCard>> Array of hands, each hand is an array of GraficCard objects
     */
    private array $hands;
    private int $activeHandIndex;
    private int $balance;
    private int $betAmount;
    /**
     * @var array<int, bool> Array indicating whether each hand is a split hand
     */
    private array $splitHands;

    public function __construct(int $numHands = 1)
    {
        // $this->hand = [];
        $this->activeHandIndex = 0;
        $this->balance = 200;
        $this->betAmount = 0;
        $this->hands = [];
        $this->splitHands = [];
        for ($i = 0; $i < $numHands; $i++) {
            $this->hands[] = [];
            $this->splitHands[] = false;
        }
    }
    /**
     * Draw a specified number of cards from the deck.
     *
     * @return GraficCard[] Array of GraficCard objects representing the drawn cards
     */

    public function getHand(int $index): array
    {
        return $this->hands[$index];
    }
    /**
     * @return GraficCard[][]
     */
    public function getHands(): array
    {
        return $this->hands;
    }
    public function getactiveHand(): int
    {
        return $this->activeHandIndex;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }
    public function getBetAmount(): int
    {
        return $this->betAmount;
    }
    public function bet(int $amount): bool
    {
        $this->betAmount = $amount;
        $numOfBets = count($this->hands);
        $totalAmount = $this->betAmount * $numOfBets;
        if ($totalAmount <= $this->balance) {
            $this->balance -= $totalAmount; // Dra av satsat belopp från balansen
            return true;
        }
        return false;
    }

    public function winTokens(): void
    {
        $this->balance += $this->betAmount * 2;
    }
    public function equal(): void
    {
        $this->balance += $this->betAmount;
    }


    public function addCard(DeckOfCards $deck): void
    {

        $numberOfCard = 1;
        $draw = $deck->draKort($numberOfCard);
        $card = $draw[0];
        $this->hands[$this->activeHandIndex][] = $card;
    }
    /**
     * @SuppressWarnings(PHPMD)
     */
    public function nextHand(): void
    {
        if ($this->activeHandIndex == count($this->hands) - 1) {
            $this->activeHandIndex = 0;
        } else {

            $this->activeHandIndex++;

        }
    }


    public function getScore(int $index): int
    {
        $score = 0;
        $aceCount = 0;

        // Associativ array för att mappa kortvärden till numeriska värden
        $cardValues = [
            'Kung' => 10,
            'Knekt' => 10,
            'Dam' => 10
        ];

        foreach ($this->hands[$index] as $card) {
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

    public function logic(): bool
    {
        $score = $this->getScore($this->activeHandIndex); // Spara bankens poäng i en variabel

        // Upprepa tills bankens poäng är 17 eller mer
        if ($score > 21) {
            return true;
        }
        return false;
    }

    public function setNumHands(int $numHands): void
    {
        $this->hands = [];
        $this->splitHands = [];
        for ($i = 0; $i < $numHands; $i++) {
            $this->hands[] = [];
            $this->splitHands[] = false;

        }
    }

    public function split(): void
    {
        $currentHand = $this->hands[$this->activeHandIndex];

        $newHand = [array_pop($currentHand)];
        $this->hands[$this->activeHandIndex] = $currentHand;
        array_splice($this->hands, $this->activeHandIndex + 1, 0, [$newHand]);
        $this->splitHands[$this->activeHandIndex] = true;
        array_splice($this->splitHands, $this->activeHandIndex + 1, 0, true);
        $this->balance -= $this->betAmount;

    }

    public function splitHands(): bool
    {
        $cardValues = [
            'Kung' => 10,
            'Knekt' => 10,
            'Dam' => 10
        ];

        $currentHand = $this->hands[$this->activeHandIndex];

        if (count($currentHand) == 2) {
            $firstCardValue = $currentHand[0]->getValue();
            $secondCardValue = $currentHand[1]->getValue();

            $firstCardActualVal = $cardValues[$firstCardValue] ?? $firstCardValue;
            $secondCardActualVal = $cardValues[$secondCardValue] ?? $secondCardValue;

            if ($firstCardActualVal == $secondCardActualVal && !$this->splitHands[$this->activeHandIndex]) {

                return true;
            }
            return false;

        }
        return false;


    }

}
