<?php

namespace App\Controller;

use App\Cards\DeckOfCards;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardGameController extends AbstractController
{
    #[Route('/session', name: 'session_show')]
    public function show(SessionInterface $session): Response
    {
        $sessionData = $session->all();

        return $this->render('cards/session.html.twig', [
            'sessionData' => $sessionData,
        ]);
    }

    #[Route('/session/delete', name: 'session_delete')]
    public function delete(SessionInterface $session): Response
    {
        $session->clear();
        $this->addFlash(
            'notice',
            "nu är sessionen raderad"
        );
        return $this->redirectToRoute('session_show');
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
        $deck = new DeckOfCards();
        $deck->shuffle();
        $session->set("deck", $deck);

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
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set("deck", $deck);
        }
        // Ensure $deck is always an instance of DeckOfCards
        /** @var DeckOfCards $deck */
        $deck = $session->get("deck");
        $antalKort = $deck->count();
        if ($antalKort <= 0) {
            $newDeck = new DeckOfCards();
            $session->set("deck", $newDeck);
            $deck = $session->get("deck");

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
