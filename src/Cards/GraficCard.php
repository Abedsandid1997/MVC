<?php

namespace App\Cards;

class GraficCard extends Cards
{
    protected string $imagePath;
    public function __construct(string $suit, string $value)
    {
        parent::__construct($suit, $value);
        $this->imagePath = 'img/' . $this->value . $this->suit . '.png';
    }

    public function getCard(): string
    {
        return $this->imagePath;
    }
}
