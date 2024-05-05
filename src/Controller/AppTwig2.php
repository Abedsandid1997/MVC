<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppTwig2 extends AbstractController
{
    #[Route("/report/kmom01", name: "kmom01")]
    public function kmom01(): Response
    {
        return $this->render('kmom01.html.twig');
    }

    #[Route("/report/kmom02", name: "kmom02")]
    public function kmom02(): Response
    {
        return $this->render('kmom02.html.twig');
    }

    #[Route("/report/kmom03", name: "kmom03")]
    public function kmom03(): Response
    {
        return $this->render('kmom03.html.twig');
    }

    #[Route("/report/kmom04", name: "kmom04")]
    public function kmom04(): Response
    {
        return $this->render('kmom04.html.twig');
    }

    #[Route("/report/kmom05", name: "kmom05")]
    public function kmom05(): Response
    {
        return $this->render('kmom05.html.twig');
    }

    #[Route("/report/kmom06", name: "kmom06")]
    public function kmom06(): Response
    {
        return $this->render('kmom06.html.twig');
    }

}
