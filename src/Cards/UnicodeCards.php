<?php

namespace App\Cards;

class UnicodeCards extends Cards
{
    public function getCard(): string
    {
        // Skapa en array med Unicode-tecken för varje svit
        $suits = [
            'hearts' => "\u{2665}",
            'diamonds' => "\u{2666}",
            'clubs' => "\u{2663}",
            'spades' => "\u{2660}"
        ];

        // Returnera kortet som en sträng med Unicode-tecken för både värde och svit
        return "[{$this->value}{$suits[$this->suit]}]";
    }
}
