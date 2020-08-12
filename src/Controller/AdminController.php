<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Psr\Log\LoggerInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Formation;
use App\Repository\FormationRepository;
use App\Data\SearchData;
use App\Form\SearchFormType;
use Knp\Component\Pager\PaginatorInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(TokenStorageInterface $tokenStorage, UserRepository $repository, Request $request,  PaginatorInterface $paginator)
    {

        $user = $tokenStorage->getToken()->getUser();


        // $collab  = $repository->findCollab();
        // $pagination = $paginator->paginate(
        //     $collab, /* query NOT result */
        //     $request->query->getInt('page', 1)/*page number*/,
        //     10/*limit per page*/
        // );
        $formations = $this->getDoctrine()->getRepository(Formation::class)->findBy(array(), array('created_at' => 'DESC'));

        return $this->render('admin/index.html.twig', [
            'user' => $user, 'formations' => $formations,
        ]);
    }

    /**
     * @Route("/admin/collabs", name="adminCollab")
     */
    public function collab(TokenStorageInterface $tokenStorage, UserRepository $repository, Request $request,  PaginatorInterface $paginator)
    {

        $user = $tokenStorage->getToken()->getUser();


        $collab  = $repository->findCollab();
        $pagination = $paginator->paginate(
            $collab, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        //$formations = $this->getDoctrine()->getRepository(Formation::class)->findBy(array(), array('created_at' => 'DESC'));
        return $this->render('admin/collaborateurs.html.twig', [
            'user' => $user, 'collab' => $collab,'pagination'=>$pagination
        ]);
 
    }


    /**
     * @Route("/admin/{id}", name="Valider")
     */
    public function valider($id, Request $request, UserRepository $repository, \Swift_Mailer $mailer, LoggerInterface $logger)
    {

        $formation = $this->getDoctrine()->getRepository(Formation::class)->find($id);
        $msg = "Votre publication " . $formation->getTitle() . " est acceptée. \n Cordialement.";
        if ($formation->getStatut() == 'en attente') {
            $formation->setStatut('validée');
            $formation->setCreatedAt(new \DateTime());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $name = $request->query->get('username');
            $message = new \Swift_Message('Validation de la ' . $formation->getTitle());
            $message->setFrom('admin@gmail.com');
            $message->setTo($formation->getUser()->getEmail())
                ->setBody(
                    $msg,
                    'text/plain',
                    'utf-8'
                );



            $mailer->send($message);
            $logger->info('Email d acceptation est envoyé.');


            $this->addFlash('notice', 'la Formation est validée');
        }

        return $this->redirect($this->generateUrl('admin'));
    }

    /**
     * @Route("/refuser/{id}", name="refuser")
     */
    public function refuser($id, Request $request, FormationRepository $repository, \Swift_Mailer $mailer, LoggerInterface $logger)
    {

        $formation = $this->getDoctrine()->getRepository(Formation::class)->find($id);
        $msg = "Votre publication " . $formation->getTitle() . " est refusée. \n Merci de republier en respectant nos conditions.";
        if ($formation->getStatut() == 'en attente') {
            $formation->setStatut('refusée');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            $name = $request->query->get('username');
            $message = new \Swift_Message('Refus de formation ' . $formation->getTitle());
            $message->setFrom('admin@gmail.com');
            $message->setTo($formation->getUser()->getEmail())
                ->setBody(
                    $msg,
                    'text/plain',
                    'utf-8'
                );



            $mailer->send($message);
            $logger->info('Email de refus est envoyé.');
            $this->addFlash('notice', 'Email de refus est envoyé.');
        }

        return $this->redirectToRoute('admin');
    }


    /**
     * @Route("/admin/formation/delete/{id}", name="deleteFormation")
     * 
     */
    public function deleteF($id, Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $f = $this->getDoctrine()->getRepository(Formation::class)->find($id);
        //the remove() method notifies Doctrine that you'd like to remove the given object from the database.        
        $entityManager->remove($f);
        //execute the DELETE query 
        $entityManager->flush();
    
        return $this->redirectToRoute('admin', ['f' => $f]);
    }

        /**
     * @Route("/admin/collab/{id}", name="Validation")
     */
    public function validation($id, Request $request, UserRepository $repository, \Swift_Mailer $mailer, LoggerInterface $logger)
    {

        $collab = $this->getDoctrine()->getRepository(User::class)->find($id);
        $msg = "Votre publication " . $collab->getName() . " est acceptée. \n Bienvenue à notre communauté.";
        if ($collab->getStatut() == 'en attente') {
            $collab->setStatut('validé');
            $collab->setRoles(["ROLE_COLLABORATEUR"]);
            $entityManager = $this->getDoctrine()->getManager();

            $name = $request->query->get('username');
            $message = new \Swift_Message('Validation de la ' . $collab->getName());
            $message->setFrom('admin@gmail.com');
            $message->setTo($collab->getEmail())
                ->setBody(
                    $msg,
                    'text/plain',
                    'utf-8'
                );



            $mailer->send($message);
            $logger->info('Email d acceptation est envoyé.');


            $this->addFlash('notice', 'Validation avec succès');
            $entityManager->flush();
        }

        return $this->redirect($this->generateUrl('adminCollab')); 
    }
}
