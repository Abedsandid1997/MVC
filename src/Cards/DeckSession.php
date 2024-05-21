<?php

namespace App\Cards;

use App\Game\DeckOfCards;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DeckSession
{
    public function initializeDeck(SessionInterface $session): DeckOfCards
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set("deck", $deck);
        }

        return $session->get("deck");
    }

    public function resetDeck(SessionInterface $session): DeckOfCards
    {
        $newDeck = new DeckOfCards();
        $session->set("deck", $newDeck);
        return $newDeck;
    }


}
