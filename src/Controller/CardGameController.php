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


    #[Route("/card/deck/draw/cards", name: "draw_cards_get", methods: ['GET'])]
    public function init(SessionInterface $session): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set("deck", $deck);
        }
        $deck = $session->get("deck");
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

        return $this->redirectToRoute('draw_cards_post', ['num_cards' => $numberOfCards]);
    }

    #[Route("/card/deck/draw/{num_cards}", name: "draw_cards_post", methods: ['GET'])]
    public function initCallback(
        int $num_cards,
        SessionInterface $session
    ): Response {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set("deck", $deck);
        }
        $deck = $session->get("deck");
        $antalKort = $deck->count();

        if ($antalKort <= 0) {
            $newDeck = new DeckOfCards();
            $session->set("deck", $newDeck);
            $deck = $session->get("deck");
            $this->addFlash(
                'notice',
                "Kortleken har blivit omställd eftersom alla 52 kort har dragits. Du kan fortsätta att dra kort som vanligt"
            );

        } elseif ($num_cards > $antalKort) {
            $this->addFlash(
                'warning',
                "You can't draw more than $antalKort"
            );
            $data = [

                "antalkort" => $antalKort
            ];
            return $this->render('cards/drawcards.html.twig', $data);

        }
        $draw = $deck->draKort($num_cards);
        $antalKort = $deck->count();

        $data = [
            "cards" => $draw,
            "antal" => $num_cards,
            "antalkort" => $antalKort
        ];

        return $this->render('cards/drawcards.html.twig', $data);
    }


    #[Route("/api/deck", name: "/api/deck")]
    public function jsonDeck(): Response
    {


        $deck = new DeckOfCards();



        // Hämta alla kort från kortleken
        $cards = $deck->getCards();
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
        $deck = new DeckOfCards();
        $session->set("deck", $deck);
        $deck->shuffle(); // Shuffle the deck
        $cards = $deck->getCards();
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
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set("deck", $deck);
        }
        $deck = $session->get("deck");
        $antalKort = $deck->count();
        if ($antalKort <= 0) {
            $newDeck = new DeckOfCards();
            $session->set("deck", $newDeck);
            $deck = $session->get("deck");

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
        $numberOfCards = $request->request->get('number');
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $session->set("deck", $deck);
        }
        $deck = $session->get("deck");
        $antalKort = $deck->count();

        if ($antalKort <= 0) {
            $newDeck = new DeckOfCards();
            $session->set("deck", $newDeck);
            $deck = $session->get("deck");

        } elseif ($numberOfCards > $antalKort) {
            throw new \Exception("Can not draw more than $antalKort cards!");

        }
        $draw = $deck->draKort($numberOfCards);
        $antalKort = $deck->count();
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
