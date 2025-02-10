<?php

namespace App\Controller\Front;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/trick/{id}', name: 'app_admin_trick_show',requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function show(int $id, ?Trick $trick, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            try {
                $comment->setCreatedAt(new \DateTimeImmutable());
                $comment->setTrick($trick);
                $comment->setStatus(true);
                $comment->setUserId($this->getUser());
                $comment->setContent($commentForm->get('content')->getData());
        
                $entityManager->persist($comment);
                $entityManager->flush();
        
                $this->addFlash('success', 'Commentaire envoyé avec succès !');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'envoi du commentaire.');
            }
        
            return $this->redirectToRoute('app_admin_trick_show', ['id' => $id]);
        }
        

        return $this->render('admin/trick/show.html.twig', [
            'trick' => $trick, 'commentForm' => $commentForm->createView()
        ]);
    }
}
