<?php

namespace App\Controller;

use App\Entity\MotPartie;
use App\Entity\Partie;
use App\Repository\MotPartieRepository;
use App\Repository\MotRepository;
use App\Repository\PartieRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewPartieController extends AbstractController
{
    #[Route('/new/partie', name: 'app_new_partie')]
    public function index(
        Request $request,
        MotPartieRepository $motPartieRepository,
        PartieRepository $partieRepository,
        MotRepository $motRepository,
        UserRepository $userRepository
    ): Response
    {
        $joueur1 = $joueur2 = $userRepository->find(1);
        $partie = new Partie();
        $partie->setJoueur1($joueur1); //mettre $this->getUser()

        //récupération du J2 depuis le formulaire

        $partie->setTourJoueur($joueur1);//mettre $this->getUser()
        $partie->setEtatPartie('en attente');
        $partie->setVictoire(false);

        $partieRepository->save($partie,true);

        //récupération de 25 mots aléatoires
        $mots = $motRepository->findAll();
        $tMots = [];
        foreach ($mots as $mot) {
            $tMots[$mot->getId()] = $mot;
        }

        $tCartes = [];
        $tCartes[0][1] = 'Noir';
        $tCartes[0][2] = 'Vert';

        $tCartes[1][1] = 'Noir';
        $tCartes[1][2] = 'Noir';

        $tCartes[2][1] = 'Noir';
        $tCartes[2][2] = 'Neutre';

        $tCartes[3][1] = 'Vert';
        $tCartes[3][2] = 'Noir';

        $tCartes[4][1] = 'Neutre';
        $tCartes[4][2] = 'Noir';

        $tCartes[5][1] = 'Vert';
        $tCartes[5][2] = 'Vert';

        $tCartes[6][1] = 'Vert';
        $tCartes[6][2] = 'Vert';

        $tCartes[7][1] = 'Vert';
        $tCartes[7][2] = 'Vert';

        for($i=8; $i<15; $i++) {
            $tCartes[$i][1] = 'Neutre';
            $tCartes[$i][2] = 'Neutre';
        }

        for($i=15; $i<20; $i++) {
            $tCartes[$i][1] = 'Vert';
            $tCartes[$i][2] = 'Neutre';
        }

        for($i=20; $i<25; $i++) {
            $tCartes[$i][1] = 'Neutre';
            $tCartes[$i][2] = 'Vert';
        }
        shuffle($tCartes);
        shuffle($tMots); //mélange du tableau

        for($i=0;$i<25;$i++) {
            $mp = new MotPartie();
            $mp->setPartie($partie);
            $mp->setMot(array_pop($tMots));
            $mp->setCouleurJ1($tCartes[$i][1]);
            $mp->setCouleurJ2($tCartes[$i][2]);
            $mp->setEmplacement($i);
            $mp->setTrouve(false);
            $motPartieRepository->save($mp,true);
        }
        return $this->redirect('https://mmi21a11.sae401.ovh/jeu/'.$partie->getId().'/1');

    }
}
