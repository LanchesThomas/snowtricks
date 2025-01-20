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

    #[Route('/{id}/edit', name: 'app_admin_trick_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[Route('/new', name: 'app_admin_trick_new', methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function new(?Trick $trick,Request $request,  EntityManagerInterface $entityManager): Response
    {
        $trick ??= new Trick();
        $form = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $trick->setName($form->get('name')->getData());
            $trick->setDescription($form->get('description')->getData());
            $trick->setGroupname($form->get('groupname')->getData());
            $trick->setCreatedAt($trick->createdAt ?? new \DateTimeImmutable());
            $trick->setUpdatedAt($trick->updateAt ?? new \DateTimeImmutable());
            $trick->setUserId($this->getUser());
            foreach ($form->get('media') as $mediaForm) {
                $file = $mediaForm->get('url')->getData(); // Récupérer le fichier depuis le champ non mappé
                
                if ($file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
                    // Générer un nom de fichier unique
                    $filename = uniqid().'.'.$file->guessExtension();
            
                    // Déplacer le fichier vers le répertoire configuré
                    $destination = $this->getParameter('uploads_directory');
                    $file->move($destination, $filename);
            
                    // Mettre à jour l'entité Media avec le chemin du fichier
                    $media = $mediaForm->getData(); // Récupérer l'entité Media associée
                    $media->setUrl('/uploads/'.$filename); // Chemin public relatif au répertoire web
            
                    // Associer le média au Trick
                    $media->setTrick($trick);
                    
                }
            }

            $entityManager->persist($trick);
            $entityManager->flush();

        }
        
        return $this->render('admin/trick/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_admin_trick_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function delete(Trick $trick, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifier le jeton CSRF pour éviter les suppressions non autorisées
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            // Supprimer l'entité Trick
            $entityManager->remove($trick);
            $entityManager->flush();

            // Message de confirmation pour l'utilisateur
            $this->addFlash('success', 'Trick supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Échec de la suppression. Jeton CSRF invalide.');
        }

        // Rediriger vers la liste ou une autre page
        return $this->redirectToRoute('app_home');
    }



}
