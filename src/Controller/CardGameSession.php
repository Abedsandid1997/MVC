<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardGameSession extends AbstractController
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





}
