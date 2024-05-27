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
class BlackJack extends AbstractController
{
    #[Route("/proj", name: "proj")]
    public function game(): Response
    {
        return $this->render('projekt/game.html.twig');
    }

    #[Route("/proj/redovisning", name: "proj-redovisning")]
    public function redovisning(): Response
    {
        return $this->render('projekt/redovisning.html.twig');
    }

    #[Route("/proj/game/share", name: "game_share")]
    public function share(SessionInterface $session): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $game = new Game();

            $session->set("deck", $deck);
            $session->set("game", $game);
        }
        $game = $session->get("game");
        $deck = $session->get("deck");

        $betAdvice = $deck->getAdvice();

        $player = $game->getPlayer();
        $playerBalance = $player->getBalance();
        $data = [
            "player_balance" => $playerBalance,
            "betAdvice" => $betAdvice

        ];
        return $this->render('projekt/share.html.twig', $data);
    }


    #[Route("/proj/about", name: "about")]
    public function doc(): Response
    {
        return $this->render('projekt/about.html.twig');
    }

    #[Route("/proj/game/bank/draw", name: "bank-draw", methods: ['GET'])]
    public function init(SessionInterface $session): Response
    {
        if (!$session->has('deck')) {
            $deck = new DeckOfCards();
            $game = new Game();

            $session->set("deck", $deck);
            $session->set("game", $game);
        }
        $deck = $session->get("deck");
        $antalKort = $deck->count();
        if ($antalKort <= 9) {
            $deck = new DeckOfCards();
            $deck->shuffle();

            $session->set("deck", $deck);
        }

        $game = $session->get("game");





        $player = $game->getPlayer();



        $playerScore = [];
        $hands = [];
        $antalHands = $game->getHandsNumber();
        for ($i = 0; $i < $antalHands; $i++) {

            $playerScore[] = $player->getScore($i);
            $hands[] =  $player->getHand($i);
            $player->nextHand();

        }




        $bank = $game->getBank();

        $bank->play($deck);
        $bankCard = $bank->getHand();
        $bankCards = $bank->getScore();
        $antalKort = $deck->count();
        $winner = $game->isWinner();

        $playerBalance = $player->getBalance();
        $data = [
            "hands" => $hands,
            "antal" => $antalKort,
            "playerScore" => $playerScore,
            "bank" => $bankCard,
            "BankCards" => $bankCards,
            "result" => $winner,
            "player_balance" => $playerBalance,

        ];
        return $this->render('projekt/bank.html.twig', $data);
    }
    #[Route("/proj/game/player/draw", name: "player-draw", methods: ['GET'])]
    public function draw(SessionInterface $session): Response
    {

        // if (!$session->has('deck')) {
        //     $deck = new DeckOfCards();
        //     $game = new Game();
        //     $session->set("deck", $deck);
        //     $session->set("game", $game);
        // }

        $deck = $session->get("deck");
        $antalKort = $deck->count();
        if ($antalKort <= 0) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set("deck", $deck);
        }






        $game = $session->get("game");
        $player = $game->getPlayer();
        // $playerBalance = $player->getBalance();

        $player->addcard($deck);
        $logic = $player->logic();
        if ($logic) {

            return $this->redirectToRoute('next_hand');

        }

        return $this->redirectToRoute('table');

    }



    #[Route('/proj/next/hand', name: 'next_hand')]
    public function nextHand(SessionInterface $session): Response
    {
        $game = $session->get("game");
        $player = $game->getPlayer();
        // $playerBalance = $player->getBalance();

        $currentHand = $player->getactiveHand();
        $antalHands = $game->getHandsNumber();
        if ($antalHands === $currentHand + 1) {
            $player->nextHand();
            return $this->redirectToRoute('bank-draw');

        }

        $this->addFlash(
            'notice',
            "Next hand"
        );

        $player->nextHand();

        return $this->redirectToRoute('table');

    }







    #[Route('/proj/split/hand', name: 'split')]
    public function split(SessionInterface $session): Response
    {
        $game = $session->get("game");
        $player = $game->getPlayer();

        $playerBalance = $player->getBalance();
        $playerBet = $player->getBetAmount();
        if($playerBet > $playerBalance) {
            $this->addFlash(
                'warning',
                "not enough Tokens to split"
            );
            return $this->redirectToRoute('table');

        }
        $game->playersplit();
        return $this->redirectToRoute('table');

    }



    #[Route('/proj/new/game', name: 'proj_new_game')]
    public function delete(SessionInterface $session): Response
    {
        $session->clear();

        return $this->redirectToRoute('game_share');
    }

    #[Route("/proj/game/player/start", name: "game-player-start", methods: ['POST'])]
    public function init2(SessionInterface $session, Request $request): Response
    {
        $cardCount = $request->request->get('hands-num');
        $betAmount = $request->request->get('bet-amount');
        $intelligence = $request->request->get('intelligence');



        $game = $session->get("game");

        $deck = $session->get("deck");
        $game->changeNumHands($cardCount);

        $player = $game->getPlayer();
        $bet = $player->bet($betAmount);
        if (!$bet) {
            $this->addFlash(
                'warning',
                "LOW Tokens"
            );
            return $this->redirectToRoute('game_share');

        }
        $antalKort = $deck->count();
        if ($antalKort <= 7) {
            $deck = new DeckOfCards();
            $deck->shuffle();
            $session->set("deck", $deck);

        }
        $game->changeIntelligenceLevel($intelligence);

        $game->shareCards($deck);


        return $this->redirectToRoute('table');

    }


    #[Route("/proj/game/palying/table", name: "table")]
    public function table(SessionInterface $session): Response
    {

        $deck = $session->get("deck");


        $game = $session->get("game");
        $player = $game->getPlayer();


        $playerCard = [];
        $hands = [];
        $antalHands = $game->getHandsNumber();

        for ($i = 0; $i < $antalHands; $i++) {

            $playerCard[] = $player->getScore($i);
            $hands[] =  $player->getHand($i);
            $player->nextHand();

        }

        $playerBalance = $player->getBalance();

        $bank = $game->getBank();
        $bankHand =  $bank->getHand();
        $bankScore = $bank->getScore();




        $antalKort = $deck->count();
        $currentHand = $player->getactiveHand();
        $playerScore = $player->getScore($currentHand);

        $splitChecker = $player->splitHands();
        $statistics = $deck->getStatistics();
        $ProbabilityOfBusting = $deck->ProbabilityOfBusting($playerScore);
        $data = [
            "hands" => $hands,
            "antal" => $antalKort,
            "value" => $playerCard,
            "bankhand" => $bankHand,
            "bankscore" => $bankScore,
            "currentHand" => $currentHand,
            "player_balance" => $playerBalance,
            "split_hands" => $splitChecker,
            "statistics" => $statistics,
            "ProbabilityOfBusting" => $ProbabilityOfBusting

        ];
        return $this->render('projekt/player.html.twig', $data);
    }


}
