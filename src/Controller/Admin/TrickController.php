<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Entity\Trick;
use App\Form\TricksType;
use App\Repository\MediaRepository;
use App\Repository\TrickRepository;
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
    public function manageTrick(?Trick $trick, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Déterminer si c'est une création ou une modification
        $isNew = !$trick; 
    
        // Si $trick est null, on crée un nouvel objet Trick
        $trick ??= new Trick();
    
        $form = $this->createForm(TricksType::class, $trick);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $trick->setCreatedAt($trick->getCreatedAt() ?? new \DateTimeImmutable());
                $trick->setUpdatedAt(new \DateTimeImmutable());
                $trick->setUserId($this->getUser());
    
                // Gérer l'upload des médias
                $mediaForms = $form->get('media'); // Récupère la collection des médias
                foreach ($mediaForms as $mediaForm) {
                    $file = $mediaForm->get('url')->getData();
                    if ($file instanceof \Symfony\Component\HttpFoundation\File\UploadedFile) {
                        // Générer un nom unique pour le fichier
                        $filename = uniqid().'.'.$file->guessExtension();
                        $destination = $this->getParameter('uploads_directory');
                        $file->move($destination, $filename);
    
                        // Créer et associer un nouvel objet Media
                        $media = new Media();
                        $media->setUrl('/uploads/'.$filename);
                        $media->setTrick($trick);
    
                        $entityManager->persist($media);
                    }
                }
    
                $entityManager->persist($trick);
                $entityManager->flush();
    
                // Définir un message flash en fonction de l'action
                $message = $isNew ? 'Le trick a été ajouté avec succès !' : 'Le trick a été mis à jour avec succès !';
                $this->addFlash('success', $message);
    
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'enregistrement du trick.');
            }
    
            return $this->redirectToRoute('app_home'); // Redirection après traitement
        }
    
        return $this->render('admin/trick/new.html.twig', [
            'form' => $form,
            'trick' => $trick,
            'isNew' => $isNew // Utile pour le template
        ]);
    }
    

    

    #[Route('/{id}/delete', name: 'app_admin_trick_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function delete(Trick $trick, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Vérifier le jeton CSRF pour éviter les suppressions non autorisées
        if (!$this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $this->addFlash('danger', 'Échec de la suppression. Jeton CSRF invalide.');
            return $this->redirectToRoute('app_home');
        }
    
        try {
            // Supprimer l'entité Trick
            $entityManager->remove($trick);
            $entityManager->flush();
    
            // Message de confirmation
            $this->addFlash('success', 'Trick supprimé avec succès.');
        } catch (\Exception $e) {
            // Gestion des erreurs en cas de problème avec la suppression
            $this->addFlash('danger', 'Une erreur est survenue lors de la suppression du trick.');
        }
    
        // Redirection après la suppression
        return $this->redirectToRoute('app_home');
    }
    

    #[Route('/trick/{id}/media/{mediaId}/delete', name: 'app_trick_media_delete', methods: ['POST'])]
    public function deleteMedia(int $id, int $mediaId, TrickRepository $trickRepository, MediaRepository $mediaRepository, EntityManagerInterface $entityManager): Response
    {
        $trick = $trickRepository->find($id);
        $media = $mediaRepository->find($mediaId);

        if (!$trick || !$media || !$trick->getMedia()->contains($media)) {
            throw $this->createNotFoundException('Trick or media not found.');
        }

        // Supprimez l'image
        $trick->removeMedium($media); // Assurez-vous que cette méthode existe dans votre entité Trick
        $entityManager->remove($media); // Suppression de l'entité Media
        $entityManager->flush();

        $this->addFlash('success', 'L\'image a été supprimée avec succès.');

        return $this->redirectToRoute('app_admin_trick_edit', ['id' => $id]);
    }


    



}
