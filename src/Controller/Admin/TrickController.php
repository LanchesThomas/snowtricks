<?php

namespace App\Controller\Admin;

use App\Entity\Trick;
use App\Form\TricksType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/trick')]
class TrickController extends AbstractController
{
    #[Route('', name: 'app_admin_trick')]
    public function index(): Response
    {
        return $this->render('admin/trick/index.html.twig', [
            'controller_name' => 'TrickController',
        ]);
    }

    #[Route('/new', name: 'app_admin_trick_new', methods: ['GET'])]
    public function new(): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TricksType::class, $trick);
        
        return $this->render('admin/trick/new.html.twig', [
            'form' => $form,
        ]);
    }
}
