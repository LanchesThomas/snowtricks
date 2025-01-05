<?php

namespace App\Controller;

use App\Form\ConnexionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/connexion', name: 'app_login', methods:['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ConnexionFormType::class, []);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) { 
            $data = $form->getData();
            $mail = $data['mail'];
            $password = $data['password'];

        }




        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
        ]);
    }
}
