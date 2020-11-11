<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Formation;
use App\Repository\FormationRepository;
use App\Entity\Session;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\SessionRepository;
use App\Form\SessionType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DetailController extends AbstractController
{
    /**
     * @Route("/detail/{id}", name="detail")
     */
    public function index($id, Request $request,SessionRepository $repository,UserRepository $repo, TokenStorageInterface $tokenStorage)
    {
        $user = $tokenStorage->getToken()->getUser();
        $formation = $this->getDoctrine()->getRepository(Formation::class)->find($id);
        $sessions = $repository->findBy(['formation'=>$formation]);
        $users = $this->getDoctrine()->getRepository(User::class)
                    ->createQueryBuilder('u')
                    ->innerJoin('u.sessions', 's');
        $equipe = $repo->FindEq($formation);

        $session=new Session();
      
        $form = $this->createForm(SessionType::class, $session);
        //handle request
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
 
           $session->setFormation($formation);
           $entityManager = $this->getDoctrine()->getManager();
           $entityManager->persist($session);
           $entityManager->flush();
           return $this->redirectToRoute('detail', ['id' => $id]);
        }
        return $this->render('detail/index.html.twig', [
           'users'=>$users, 'formation' => $formation, 'user' => $user, 'sessions' => $sessions,'equipe'=>$equipe,'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/incrire/{id}", name="inscription")
     */
    public function inscription($id,TokenStorageInterface $tokenStorage,SessionRepository $repository,Request $request)
    {
        $user = $tokenStorage->getToken()->getUser();
        // $formation = $this->getDoctrine()->getRepository(Formation::class)->find($id);
        $s = $this->getDoctrine()->getRepository(Session::class)->find($id);
        if (!$s->getUsers()->contains($user) and $s->getNbrePlaces()>0) { 
       
            $s->addUser($user);

        
        $s->setNbrePlaces($s->getNbrePlaces()-1);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($s);
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('info', 'Vous etes inscris');
        }
        else{
            $this->addFlash('info', 'Vous etes déjà inscris. ');
        }

        
        return $this->redirectToRoute('detail', ['id' => $s->getFormation()->getId(),'user'=>$user]);
        

       
        
    }
}
