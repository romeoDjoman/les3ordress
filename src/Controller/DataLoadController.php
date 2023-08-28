<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DataLoadController extends AbstractController
{
    #[Route('/data', name: 'app_data_load')]
    public function index(UserRepository $repoUser,EntityManagerInterface $manager): Response
    {
        $userAdmin=$repoUser->find(2);
        $userAdmin->setRoles(['ROLE_ADMIN']);
        $manager->flush();
        return $this->render('data_load/index.html.twig', [
            'message'=>'Bienvenu sur votre controllerde mise a jour avec success'
           
        ]);
    }
}
