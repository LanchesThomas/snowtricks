<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(TrickRepository $tricksRepository): Response
    {
        $tricks = $tricksRepository->findBy([], ['createdAt' => 'DESC']); // Tri du plus récent au plus ancien
        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
        ]);
    }
}
