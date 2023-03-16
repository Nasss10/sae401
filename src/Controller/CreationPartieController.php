<?php

namespace App\Controller;
use App\Entity\Partie;
use App\Form\CreationPartieType;
use App\Repository\PartieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class CreationPartieController extends AbstractController
{
    #[Route('/creation/partie', name: 'app_creation_partie')]
    public function new(Request $request, EntityManagerInterface $em, Security $security): Response
    {
        $partie = new Partie($security);
        $form = $this->createForm(CreationPartieType::class, $partie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $partie = $form->getData();

            // Utilisez $securit cué"è i!jjéaz§qtngy pour récupérer l'utilisateur connecté et son ID
            $user = $security->getUser();
            $userId = $user->getId();

            // Assigne l'ID de l'utilisateur connecté à l'entité Partie
            $partie->setJoueur1($user);

            $em->persist($partie);
            $em->flush();

        }
        return $this->render('creation_partie/index.html.twig', [
            'controller_name' => 'CreationPartieController',
            'form' => $form
        ]);
    }
}

