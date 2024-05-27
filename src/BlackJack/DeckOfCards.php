<?php

namespace App\BlackJack;

use App\BlackJack\GraficCard;
use App\BlackJack\Counter;

class DeckOfCards
{
    /**
     * @var GraficCard[] Array of GraficCard objects
    */
    private array $cards;
    private Counter $counter;
    public function __construct()
    {

        $this->cards = [];
        $suits = array('diamonds', 'hearts', 'clubs', 'spades');
        $values = array( 'Ess','2', '3', '4', '5', '6', '7', '8' , '9' , '10', 'Knekt', 'Dam', 'Kung');
        $this->counter = new Counter();
        for ($i = 0; $i < 6; $i++) {
            foreach ($suits as $suit) {
                foreach ($values as $value) {
                    $this->cards[] = new GraficCard($suit, $value);
                }
            }
        }
    }
    /**
     * Get the cards in the deck.
     *
     * @return GraficCard[] Array of GraficCard objects
    */
    public function getCards(): array
    {
        return $this->cards ;
    }

    public function shuffle(): void
    {
        shuffle($this->cards);
    }


    public function count(): int
    {
        return(count($this->cards));
    }

    /**
     * Draw a specified number of cards from the deck.
     *
     * @param int $number The number of cards to draw
     * @return GraficCard[] Array of GraficCard objects representing the drawn cards
     */
    public function draKort(int $number): array
    {
        $mycards = [];
        for ($i = 1; $i <= $number; $i++) {
            // $antalKort = count($this->cards);
            $index = 0;
            $mycards[] = $this->cards[$index];
            unset($this->cards[$index]);
            $this->cards = array_values($this->cards);
            $this->counter->updateStatistics($mycards[0]->getValue());
        }

        return $mycards;
    }
    /**
     * @return array<string, int> Array mapping card values to their counts
     */
    public function getStatistics(): array
    {
        return $this->counter->getStatistics();
    }

    public function setCounter(Counter $counter): void
    {
        $this->counter = $counter;
    }
    public function getAdvice(): string
    {


        $runingCount =  $this->counter->getRunningCount();
        $trueCount = $runingCount / ($this->count() / 52);
        if ($trueCount >= 1) {
            return "Higher Bet";
        } elseif ($trueCount <= -1) {
            return "Lower Bet";
        }
        return "Maintain Bet";

    }
    public function probabilityOfBusting(int $currentHandValue): float
    {


        return $this->counter->probabilityOfBusting($currentHandValue);

    }

}
