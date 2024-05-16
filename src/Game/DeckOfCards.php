<?php

namespace App\Game;

use App\Game\GraficCard;

class DeckOfCards
{
    /**
     * @var GraficCard[] Array of GraficCard objects
    */
    private array $cards;

    public function __construct()
    {

        $this->cards = array();
        $suits = array('diamonds', 'hearts', 'clubs', 'spades');
        $values = array('Ess', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'Knekt', 'Dam', 'Kung');

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                // $index = array_search($value, $values);
                $this->cards[] = new GraficCard($suit, $value);

                // if ($index % 2 === 1) {
                //     $this->cards[] = new GraficCard($suit, $value);
                // } else {
                //     $this->cards[] = new GraficCard($suit, $value);

                // }
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
            $index = array_rand($this->cards);
            $mycards[] = $this->cards[$index];
            unset($this->cards[$index]);
        }

        return $mycards;
    }

}
