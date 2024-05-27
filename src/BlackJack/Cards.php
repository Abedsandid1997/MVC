<?php

namespace App\BlackJack;

class Cards
{
    protected string $value;
    protected string $suit;

    public function __construct(string $suit, string $value)
    {
        $this->value = $value;
        $this->suit = $suit;
    }



    public function getValue(): string
    {
        return $this->value;
    }
    public function getSuit(): string
    {
        return $this->suit;
    }
    public function getCard(): string
    {
        return  $this->value . $this->suit;
    }

}
