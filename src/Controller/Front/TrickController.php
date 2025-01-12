<?php

namespace App\Controller\Front;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TrickController extends AbstractController
{
    #[Route('/tricks', name: 'app_front_trick')]
    public function index(TrickRepository $tricksRepository): Response
    {
        $tricks = $tricksRepository->findAll();
        return $this->render('front/trick/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }
}
