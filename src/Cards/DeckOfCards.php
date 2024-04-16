<?php

namespace App\Cards;

use App\Cards\UnicodeCards;

class DeckOfCards
{
    private $cards;

    public function __construct()
    {

        $this->cards = array();
        $suits = array('diamonds', 'hearts', 'clubs', 'spades');
        $values = array('Ess', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'Knekt', 'Dam', 'Kung');

        foreach ($suits as $suit) {
            foreach ($values as $value) {
                $index = array_search($value, $values);
                if ($index % 2 === 1) {
                    $this->cards[] = new UnicodeCards($suit, $value);
                } else {
                    $this->cards[] = new GraficCard($suit, $value);

                }
            }
        }
    }

    public function getCards()
    {
        return $this->cards ;
    }

    public function shuffle()
    {
        shuffle($this->cards);
    }
    public function dra()
    {
        // $antalKort = count($this->cards);
        $index = array_rand($this->cards);
        $mycard = $this->cards[$index];
        unset($this->cards[$index]);
        return $mycard;
    }

    public function count()
    {
        return(count($this->cards));
    }

    public function draKort($number)
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
