<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\Session;
use App\Repository\SessionRepository;
use App\Data\SearchData;
use App\Form\SearchSessionType;
use App\Form\InscriptionType;
class SessionController extends AbstractController
{
    /**
     * @Route("/session", name="session")
     */
    public function index(TokenStorageInterface $tokenStorage,SessionRepository $repository,Request $request)
    {
        $user = $tokenStorage->getToken()->getUser();
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchSessionType::class, $data);
        $form->handleRequest($request);
        $session = $repository->findSearch($data,'validée');
           
        
        return $this->render('session/index.html.twig', [
            'user' => $user,'session'=>$session,'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/incription/{id}", name="inscrire")
     */
    public function inscire($id,TokenStorageInterface $tokenStorage,SessionRepository $repository,Request $request)
    {
        $user = $tokenStorage->getToken()->getUser();
        $s = $this->getDoctrine()->getRepository(Session::class)->find($id);
        if (!$s->getUsers()->contains($user) and $s->getNbrePlaces()>0) { 
       
            $s->addUser($user);

        
        $s->setNbrePlaces($s->getNbrePlaces()-1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($s);
         $entityManager->persist($user);
        $entityManager->flush();
        $this->addFlash('notice', 'Vous etes inscris');
        }else{
            $this->addFlash('notice', 'Vous etes déjà inscris. ');
        }

        
        return $this->redirectToRoute('session', ['user'=>$user]);
        

       
        
    }
}
