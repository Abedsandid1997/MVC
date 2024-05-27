<?php

namespace App\Controller;

use App\BlackJack\DeckOfCards;
use App\BlackJack\Player;
use App\BlackJack\Bank;
use App\BlackJack\Game;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * This will suppress all the PMD warnings in
 * this class.
 *
 * @SuppressWarnings(PHPMD)
 */
class BlackJackApi extends AbstractController
{
    #[Route("/proj/api", name: "proj-api")]
    public function jsonDeck(SessionInterface $session): Response
    {

        return $this->render('projekt/api.html.twig');

    }

    #[Route("/proj/api/game/new", name: "api_game_new", methods: ['POST'])]
    public function newGame(SessionInterface $session): Response
    {
        $session->clear();

        $deck = new DeckOfCards();
        $deck->shuffle();
        $game = new Game();

        $session->set("deck", $deck);
        $session->set("game", $game);

        $data = [
            "message" => 'New game started'
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("/proj/api/deck/shuffle", name: "shuffle-deck", methods: ['POST'])]
    public function shuffleDeck(
        SessionInterface $session
    ): JsonResponse {
        // Shuffle the deck and store it in session
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $game = new Game();

            $session->set("deck", $deck);
            $session->set("game", $game);
        }
        $deck = $session->get("deck");
        $antalKort = $deck->count();
        $cardData = [];
        $cards = $deck->getCards();
        foreach ($cards as $card) {
            $cardData[] = ($card->getValue() .":" .  $card->getSuit());
        }
        $data = [
            "cards" => $cardData,
            "antalKort" => $antalKort
        ];
        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }


    #[Route("proj/api/game/player/draw", name: "game_player_draw", methods: ['POST'])]
    public function jsonDrawPlayer(SessionInterface $session, Request $request): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $game = new Game();
            $session->set("deck", $deck);
            $session->set("game", $game);
        }

        $deck = $session->get("deck");
        $antalKort = $deck->count();
        if ($antalKort <= 0) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set("deck", $deck);
        }

        $game = $session->get("game");
        $player = $game->getPlayer();
        $player->addCard($deck);
        $logic = $player->logic();
        if (!$logic) {
            $message = "you still uner 21";
        } else {
            $message = "you got bust";

        }
        $currentHand = $player->getactiveHand();
        $playerHand = [];
        foreach ($player->getHand($currentHand) as $card) {
            $playerHand[] = ['value' => $card->getValue(), 'suit' => $card->getSuit()];
        }


        $data = [
            'player-hand' => $playerHand,
            'player-points' => $player->getScore($currentHand),
            'deck-count' => $deck->count(),
            'notic' => $message
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }


    #[Route("proj/api/game/bank/draw", name: "game_bank_draw", methods: ['GET'])]
    public function jsonDrawBank(SessionInterface $session): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $game = new Game();
            $session->set("deck", $deck);
            $session->set("game", $game);
        }

        $deck = $session->get("deck");
        $antalKort = $deck->count();
        if ($antalKort <= 5) {
            $deck = new DeckOfCards();
            $deck->shuffle();

            $session->set("deck", $deck);
        }
        $game = $session->get("game");

        $bank = $game->getBank();
        $bank->resetHand();
        $bank->play($deck);

        $bankHand = [];
        foreach ($bank->getHand() as $card) {
            $bankHand[] = ['value' => $card->getValue(), 'suit' => $card->getSuit()];
        }

        $data = [
            'bank-hand' => $bankHand,
            'bank-points' => $bank->getScore(),
            'deck-count' => $deck->count()
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
    #[Route("proj/api/game/winner", name: "game_winner", methods: ['GET'])]
    public function winner(SessionInterface $session): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $game = new Game();
            $session->set("deck", $deck);
            $session->set("game", $game);
        }


        $game = $session->get("game");
        $player = $game->getPlayer();
        $currentHand = $player->getactiveHand();

        $winner = $game->isWinner();
        if($winner[$currentHand] == "Wins") {
            $result = "you Win";
        } elseif($winner[$currentHand] == "Equal") {
            $result = "Equal";

        } else {
            $result = "Bank Wins";

        }
        $data = [
            'Winner' => $result
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }

    #[Route("proj/api/game/status", name: "game-status")]
    public function gameStatus(SessionInterface $session): Response
    {


        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $game = new Game();

            $session->set("deck", $deck);
            $session->set("game", $game);
        }
        $deck = $session->get("deck");
        $game = $session->get("game");

        $player = $game->getPlayer();
        $currentHand = $player->getactiveHand();

        $playerPoints = $player->getScore($currentHand);
        $playerHand =  $player->getHand($currentHand);

        $bank = $game->getBank();
        $bankHand = $bank->getHand();
        $bankPoints = $bank->getScore();

        $antalKort = $deck->count();
        $playerCards = [];
        foreach ($playerHand as $playerCard) {
            $playerCards[] = ($playerCard->getValue() .":" .  $playerCard->getSuit());
        }
        $bankCards = [];
        foreach ($bankHand as $bankCard) {
            $bankCards[] = ($bankCard->getValue() .":" .  $bankCard->getSuit());
        }

        $data = [
            "kort-antal" => $antalKort,
            "player-hand" => $playerCards,
            "player-points" => $playerPoints,
            "bank-hand" => $bankCards,
            "bank-points" => $bankPoints
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}
