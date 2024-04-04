<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class appTwig extends AbstractController
{
    #[Route("/lucky", name: "lucky_number")]
    public function number(): Response
    {
        $number = random_int(0, 100);

        $data = [
            'number' => $number
        ];

        return $this->render('lucky_number.html.twig', $data);
    }
    
    #[Route("/", name: "me")]
    public function me(): Response
    {
        return $this->render('me.html.twig');
    }

    #[Route("/about", name: "about")]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }
    #[Route("/report", name: "report")]
    public function report(): Response
    {
        return $this->render('report.html.twig');
    }

    #[Route("/api", name: "api")]
    public function routs(): Response
    {

        $json_routes = [
            "/api/quote"
        ];
        
        $data = [
            'json_routes' => $json_routes
        ];

        return $this->render('api.html.twig', $data);
    }

    #[Route("/api/quote",name: "/api/quote")]
    public function jsonNumber(): Response
    {
        $number = random_int(0, 2);

        $citat = [
            "Lycka är att göra det du älskar och älska det du gör.",
            "Möjligheter kommer sällan till dem som väntar, de kommer till dem som söker dem.",
            "Vägen till framgång är att ta ett steg i taget, men alltid framåt.",
            "Varje dag är en ny chans att göra något fantastiskt."
        ];

        $randomCitat = $citat[array_rand($citat)];

        $date = date('Y-m-d');

        $timestamp = time();

        $data = [
            'citat' => $randomCitat,
            'data' => $date,
            'time' => $timestamp
        ];

        $response = new JsonResponse($data);
        $response->setEncodingOptions(
            $response->getEncodingOptions() | JSON_PRETTY_PRINT
        );
        return $response;
    }
}