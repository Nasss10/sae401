<?php

namespace App\Controller;

use App\Repository\PartieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte', name: 'app_compte')]
    public function index(PartieRepository $partieRepository): Response
    {
        $parties = $partieRepository->findAll();

        return $this->render('compte/index.html.twig', [
            'controller_name' => 'CompteController',
            'parties' => $parties
        ]);
    }

    public function rejoindrePartie($id, $numJoueur)
    {
        // Rediriger l'utilisateur vers la page de jeu appropriée
        return $this->redirectToRoute('nom_de_la_route_vers_la_page_de_jeu', [
            'id' => $id,
            'numJoueur' => $numJoueur,
        ]);
    }


    #[Route('/update-partie/{partieId}', name: 'app_update_partie')]
    public function updatePartie(PartieRepository $partieRepository, Request $request, int $partieId): Response
    {
        $user = $this->getUser();
        $partie = $partieRepository->find($partieId);

        if ($user && $partie) {
            if ($user === $partie->getJoueur1() || $user === $partie->getJoueur2()) {
                $this->addFlash('error', 'Impossible de rejoindre la partie, vous y êtes déjà !');
            } else {
                $partie->setJoueur2($user);
                $partie->setEtatPartie("en cours");

                $this->entityManager->persist($partie);
                $this->entityManager->flush();

                // Construire l'URL de la partie en fonction de l'utilisateur connecté
                $url = 'https://mmi21a11.sae401.ovh/jeu/' . $partie->getId() . '/';
                if ($user === $partie->getJoueur1()) {
                    $url .= '1';
                } else {
                    $url .= '2';
                }

                // Rediriger l'utilisateur vers l'URL de la partie
                return $this->redirect($url);
            }
        } else {
            $this->addFlash('error', 'Impossible de rejoindre la partie.');
        }

        return $this->redirectToRoute('app_compte');
    }


    #[Route('/upload-pdp', name: 'app_upload_pdp')]
    public function uploadPdp(Request $request): Response
    {
        $user = $this->getUser();

        if ($user) {
            $pdpFile = $request->files->get('pdp');
            $pdpFileName = uniqid().'.'.$pdpFile->getClientOriginalExtension();
            $pdpFile->move($this->getParameter('pdp_directory'), $pdpFileName);
            $user->setPdp($pdpFileName);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Photo de profil mise à jour !');
        } else {
            $this->addFlash('error', 'Vous devez être connecté pour mettre à jour votre photo de profil.');
        }

        return $this->redirectToRoute('app_compte');
    }

    #[Route('/create-compte', name: 'app_create_compte')]
    public function createCompte(Request $request): Response
    {
        $user = $this->getUser();
        $dateCreation = new \DateTime();

        if ($user) {
            $user->setDateCreationCompte($dateCreation);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Compte créé avec succès !');
        } else {
            $this->addFlash('error', 'Impossible de créer un compte pour cet utilisateur.');
        }

        return $this->redirectToRoute('app_compte');
    }
}
