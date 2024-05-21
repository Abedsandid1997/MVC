<?php

namespace App\Controller;

use App\Game\DeckOfCards;
use App\Cards\DeckSession;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardGameController extends AbstractController
{
    private DeckSession $deckSession;

    public function __construct(DeckSession $deckSession)
    {
        $this->deckSession = $deckSession;
    }


    #[Route("/card", name: "card")]
    public function laddningssida(): Response
    {

        return $this->render('cards/card.html.twig');
    }


    #[Route("/card/deck", name: "card-deck")]
    public function home(): Response
    {
        $deck = new DeckOfCards();



        // Hämta alla kort från kortleken
        $cards = $deck->getCards();

        $data = [
            "cards" => $cards
        ];
        return $this->render('cards/cards.html.twig', $data);
    }

    #[Route("/card/deck/shuffle", name: "deck-shuffle")]
    public function shuffle(SessionInterface $session): Response
    {
        $deck = $this->deckSession->initializeDeck($session);
        $deck->shuffle();
        // $session->set("deck", $deck);

        // Hämta alla kort från kortleken
        $cards = $deck->getCards();

        $data = [
            "cards" => $cards
        ];
        return $this->render('cards/cards.html.twig', $data);
    }

    #[Route("/card/deck/draw", name: "deck-draw")]
    public function draw(SessionInterface $session): Response
    {
        $deck = $this->deckSession->initializeDeck($session);

        $antalKort = $deck->count();
        if ($antalKort <= 0) {
            $deck = $this->deckSession->resetDeck($session);

        }

        $numberOfCard = 1;

        $draw = $deck->draKort($numberOfCard);
        $card = $draw[0];

        $antalKort = $deck->count();
        $data = [
            "card" => $card,
            "antal" => $antalKort
        ];
        return $this->render('cards/draw.html.twig', $data);
    }




}
