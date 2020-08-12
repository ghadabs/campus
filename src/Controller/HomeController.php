<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\Formation;
use App\Repository\FormationRepository;
use App\Data\SearchData;
use App\Form\SearchHomeType;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(TokenStorageInterface $tokenStorage, FormationRepository $repository,Request $request)
    {   
        $user = $tokenStorage->getToken()->getUser();
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchHomeType::class, $data);
        $form->handleRequest($request);
        $formation = $repository->findSearch($data,'validée');

        return $this->render('home/index.html.twig', [
            'user' => $user, 'formation' => $formation,'form' => $form->createView()
        ]);
    }
}