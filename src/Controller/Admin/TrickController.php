<?php

namespace App\Controller\Admin;

use App\Entity\Trick;
use App\Form\TricksType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    #[Route('/new', name: 'app_admin_trick_new', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function new(Request $request,  EntityManagerInterface $entityManager): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $trick->setName($form->get('name')->getData());
            $trick->setDescription($form->get('description')->getData());
            $trick->setGroupname($form->get('groupname')->getData());
            $trick->setCreatedAt($trick->createdAt ?? new \DateTimeImmutable());
            $trick->setUpdatedAt($trick->updateAt ?? new \DateTimeImmutable());
            $trick->setUserId($this->getUser());

            $entityManager->persist($trick);
            $entityManager->flush();

        }
        
        return $this->render('admin/trick/new.html.twig', [
            'form' => $form,
        ]);
    }
}
