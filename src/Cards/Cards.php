<?php

namespace App\Cards;

class Cards
{
    protected $value;
    protected $suit;

    public function __construct($suit, $value)
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
