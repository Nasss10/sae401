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
                $this->addFlash('success', 'Vous avez rejoint la partie !');
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

}
