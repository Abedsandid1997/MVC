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
use Exception;

/**
 * This will suppress all the PMD warnings in
 * this class.
 *
 * @SuppressWarnings(PHPMD)
 */
class CardGameController2 extends AbstractController
{
    private DeckSession $deckSession;

    public function __construct(DeckSession $deckSession)
    {
        $this->deckSession = $deckSession;
    }


    #[Route("/card/deck/draw/cards", name: "draw_cards_get", methods: ['GET'])]
    public function init(SessionInterface $session): Response
    {

        $deck = $this->deckSession->initializeDeck($session);

        $antalKort = $deck->count();
        $data = [
            "antalkort" => $antalKort
        ];
        return $this->render('cards/drawcards.html.twig', $data);
    }
    #[Route("/return", name: "card_numbers", methods: ['post'])]
    public function init2(
        Request $request
    ): Response {
        $numberOfCards = $request->request->get('num_cards');

        return $this->redirectToRoute('draw_cards_post', ['numCards' => $numberOfCards]);
    }

    #[Route("/card/deck/draw/{numCards}", name: "draw_cards_post", methods: ['GET'])]
    public function initCallback(
        int $numCards,
        SessionInterface $session
    ): Response {

        $deck = $this->deckSession->initializeDeck($session);

        $antalKort = $deck->count();

        if ($antalKort <= 0) {
            $deck = $this->deckSession->resetDeck($session);

            $this->addFlash(
                'notice',
                "Kortleken har blivit omställd eftersom alla 52 kort har dragits. Du kan fortsätta att dra kort som vanligt"
            );

        } elseif ($numCards > $antalKort) {
            $this->addFlash(
                'warning',
                "You can't draw more than $antalKort"
            );
            $data = [

                "antalkort" => $antalKort
            ];
            return $this->render('cards/drawcards.html.twig', $data);

        }
        $draw = $deck->draKort($numCards);
        $antalKort = $deck->count();

        $data = [
            "cards" => $draw,
            "antal" => $numCards,
            "antalkort" => $antalKort
        ];

        return $this->render('cards/drawcards.html.twig', $data);
    }

}
