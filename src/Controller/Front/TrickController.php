<?php

namespace App\Controller\Front;

use App\Entity\Trick;
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

    #[Route('/trick/{id}', name: 'app_admin_trick_show',requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(?Trick $trick): Response
    {
        return $this->render('admin/trick/show.html.twig', [
            'trick' => $trick
        ]);
    }
}
