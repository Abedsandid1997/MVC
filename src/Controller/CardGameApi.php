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
class CardGameApi extends AbstractController
{
    private DeckSession $deckSession;

    public function __construct(DeckSession $deckSession)
    {
        $this->deckSession = $deckSession;
    }



    #[Route("/api/deck", name: "/api/deck")]
    public function jsonDeck(): Response
    {


        $deck = new DeckOfCards();



        // Hämta alla kort från kortleken
        $cards = $deck->getCards();
        $cardData = [];
        foreach ($cards as $card) {
            $cardData[] = ($card->getValue() .":" .  $card->getSuit());
        }
        $data = [
            "cards" => $cardData
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/api/deck/shuffle", name: "/api/deck/shuffle", methods: ['POST'])]
    public function shuffleDeck(
        SessionInterface $session
    ): JsonResponse {
        // Shuffle the deck and store it in session
        $deck = $this->deckSession->initializeDeck($session);
        $deck->shuffle(); // Shuffle the deck
        $cards = $deck->getCards();
        $cardData = [];
        foreach ($cards as $card) {
            $cardData[] = ($card->getValue() .":" .  $card->getSuit());
        }
        $data = [
            "cards" => $cardData
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("/api/deck/draw", name: "/api/deck/draw", methods: ['POST'])]
    public function apiDraw(
        SessionInterface $session
    ): JsonResponse {
        $deck = $this->deckSession->initializeDeck($session);
        $antalKort = $deck->count();
        if ($antalKort <= 0) {
            $deck = $this->deckSession->resetDeck($session);

        }

        $numberOfCard = 1;
        $draw = $deck->draKort($numberOfCard);
        $card =  ($draw[0]->getValue() .":" .  $draw[0]->getSuit());

        $antalKort = $deck->count();
        $data = [
            "card" => $card,
            "kvarkort" => $antalKort
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("api/deck/draw/{number}", name: "api/deck/draw/{number}", methods: ['POST'])]

    public function apiDradw(
        Request $request,
        SessionInterface $session
    ): JsonResponse {
        $numberOfCards = intval($request->request->get('number'));
        $deck = $this->deckSession->initializeDeck($session);
        $antalKort = $deck->count();

        if ($antalKort <= 0) {
            $deck = $this->deckSession->resetDeck($session);

        } elseif ($numberOfCards > $antalKort) {
            throw new \Exception("Can not draw more than $antalKort cards!");

        }
        $draw = $deck->draKort($numberOfCards);
        $antalKort = $deck->count();
        $cardData = [];

        foreach ($draw as $card) {
            $cardData[] = ($card->getValue() .":" .  $card->getSuit());
        }
        $data = [
            "cards" => $cardData,
            "antal" => $numberOfCards,
            "kortkvar" => $antalKort
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }



}
