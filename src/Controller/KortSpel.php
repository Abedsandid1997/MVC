<?php

namespace App\Controller;

use App\Game\DeckOfCards;
use App\Game\Player;
use App\Game\Bank;
use App\Game\Game;
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
class KortSpel extends AbstractController
{
    #[Route("/game", name: "game")]
    public function game(): Response
    {
        return $this->render('game/game.html.twig');
    }

    #[Route("/game/doc", name: "game-doc")]
    public function doc(): Response
    {
        return $this->render('game/doc.html.twig');
    }

    #[Route("/game/bank/draw", name: "game-bank-draw", methods: ['GET'])]
    public function init(SessionInterface $session): Response
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


        $playerCard = $player->getScore();
        $cards =  $player->getHand();



        $bank = $game->getBank();

        $bank->logic($deck);
        $bankCard = $bank->getHand();
        $bankCards = $bank->getScore();
        $antalKort = $deck->count();
        $winner = $game->isWinner();
        if ($winner) {
            $this->addFlash(
                'notice',
                "Congrats you win"
            );
        } else {
            $this->addFlash(
                'warning',
                "Sorry Bank wins"
            );
        }

        $data = [
            "cards" => $cards,
            "antal" => $antalKort,
            "value" => $playerCard,
            "bank" => $bankCard,
            "BankCards" => $bankCards,
        ];
        return $this->render('game/bank.html.twig', $data);
    }


    #[Route('/new/game', name: 'new_game')]
    public function delete(SessionInterface $session): Response
    {
        $session->clear();

        return $this->redirectToRoute('game-player-draw');
    }

    #[Route("/game/player/draw", name: "game-player-draw", methods: ['GET'])]
    public function init2(SessionInterface $session): Response
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

        $player->addCard($deck);
        $logic = $player->logic();
        if ($logic) {
            $this->addFlash(
                'warning',
                "sorry you lost"
            );

        }
        $playerCard = $player->getScore();
        $cards =  $player->getHand();





        $antalKort = $deck->count();

        $data = [
            "cards" => $cards,
            "antal" => $antalKort,
            "value" => $playerCard
        ];
        return $this->render('game/player.html.twig', $data);
    }

    #[Route("/api/game", name: "/api/game")]
    public function jsonDeck(SessionInterface $session): Response
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
        $playerPoints = $player->getScore();
        $playerHand =  $player->getHand();

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
