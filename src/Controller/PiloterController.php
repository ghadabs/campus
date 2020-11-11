<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\Formation;
use App\Entity\User;
use App\Entity\Session;
use App\Entity\Equipe;
use App\Repository\FormationRepository;
use App\Repository\SessionRepository;
use App\Repository\UserRepository;
use App\Form\ProposerFormationType;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\File;

class PiloterController extends AbstractController
{
    /**
     * @Route("/piloter", name="piloter")
     */
    public function index(Request $request,TokenStorageInterface $tokenStorage,FormationRepository $repository, PaginatorInterface $paginator)
    {

        $user = $tokenStorage->getToken()->getUser();
        
        $donnees = $this->getDoctrine()->getRepository(Formation::class)->findBy(['user' => $user->getId()]);

        $formations = $paginator->paginate(
            $donnees, // Requête contenant les données à paginer (ici nos articles)
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            2 // Nombre de résultats par page
        );
        return $this->render('piloter/index.html.twig', [
            'user' => $user,'formations'=>$formations
        ]);
    }


    /**
     * @Route("/piloter/modifier/{id}", name="modifier")
     */
    public function modifier($id,TokenStorageInterface $tokenStorage,FormationRepository $repository, Request $request)
    {

        $user = $tokenStorage->getToken()->getUser();

        $em = $this->getDoctrine()->getManager();
        $formation = $this->getDoctrine()->getRepository(Formation::class)->find($id);


        $originalEquipes = new ArrayCollection();
        $originalSessions = new ArrayCollection();

        foreach ($formation->getEquipes() as $eq) {
            $originalEquipes->add($eq);
        }   

        foreach ($formation->getSessions() as $s) {
            $originalSessions->add($s);
        }
    
       

        $form = $this->createForm(ProposerFormationType::class, $formation);

  
        $form->handleRequest($request);
            
        if($form->isSubmitted() && $form->isValid()){


            $g=$form['gratuit']->getData();
            if($g==1){
                $formation->setTarif('Gratuit');
            }else{
                $form['tarif']->getData();
            }


            $formation->setCreatedAt(new \DateTime());
            $formation->setUser($user);

            

            $imageFile = $form['image']->getData();
            
            if ($imageFile) {
                //get the original path
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

               
                //Move the file to the directory where photos are stored
               
                    $imageFile->move(
                        $this->getParameter('photo_directory'),
                        $newFilename
                    );

                   
                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
            $formation->setImage($newFilename);
            
           
        }

        $equipes = $form->get('equipes');
        foreach($equipes as $e){
        
            if($e->getData()!=null){
                // $formation->setUser($e->getData());
                $e->getData()->setStatut('rien');
                $e->getData()->setRoles(["ROLE_COLLABORATEUR"]);
                }
        // $file=$e->get('image')->getData();
            
    //     if ($file) {
    //         $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    //         // this is needed to safely include the file name as part of the URL
    //         $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
    //         $fichier = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

    //     // On copie le fichier dans le dossier uploads
    //     $file->move(
    //         $this->getParameter('photo_directory'),
    //         $fichier
    //     );
        
    //     // On crée l'image dans la base de données
        
    //    $e->getData()->setImage($fichier);
    // }
    }

            if ($form->getClickedButton() === $form->get('sauvegarder')){
                dump($formation);
                $this->addFlash('notice', 'la Formation est sauvegardée en tant que brouillon');
                $formation->setStatut('brouillon');

            }
            else if ($form->getClickedButton() === $form->get('Prete')){
                $this->addFlash('notice', 'la Formation est envoyée au admin pour la validation');
                $formation->setStatut('en attente');
        
            }

                foreach ($originalEquipes as $originalEquipe) {
                if ($formation->getEquipes()->contains($originalEquipe) === false) {
                    $formation->removeEquipe($originalEquipe);
                    $originalEquipe->removeFormation($formation);
                    $em->remove($originalEquipe);
//                    $em->persist($originalSpeaker);
                }
            }

            
            foreach ($originalSessions as $originalSession) {
                if ($formation->getSessions()->contains($originalSession) === false) {
                    $formation->removeSession($originalSession);
                    $originalSession->setFormation(null);
                    $em->remove($originalSession);
//                    $em->persist($originalSpeaker);
                }
            }

               //$formation=$form->getData();

            dump($form->get('sessions')->getData());
               $em->persist($formation);
               $em -> flush();
  
    return $this->redirectToRoute("modifier",['id'=>$id]);  
        }

        
        return $this->render('formations/ProposerFormation.html.twig', [
            'user' => $user,'formation'=>$formation,'form' => $form->createView(),
        ]);
    }

     /**
    * @Route("/delete/{id}", name="DeleteFormation")
    * 
    */

    //fonction de suppression des formations
    public function deleteF($id, Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();
        // trouver la formation à supprimer par son id
        $f=$this->getDoctrine()->getRepository(Formation::class)->find($id);
        //the remove() method notifies Doctrine that you'd like to remove the given object from the database.
        $entityManager->remove($f);
        //execute the DELETE query
        $entityManager->flush();
        
        return $this->redirectToRoute('piloter',['f'=>$f]);
    }


 
 /**
   * Creates a new ActionItem entity.
   *
   * @Route("/users/{id}", name="usersS")
   * 
   */
  public function getting($id)
  {
      $em = $this->getDoctrine()->getManager();
      
     $session=$em->getRepository(Session::class)->find($id);
     $users =  $em->getRepository(User::class)->findUs($session);
       
      if(!$users) {
          $result['users']['error'] = "Aucun Inscrit!";
      } else {
          $result['users'] = $this->getRealEntities($users);
     

      }


      return new Response(json_encode($result));
  }

  public function getRealEntities($users){

      foreach($users as $equipe){
          $realEntities[$equipe->getId()] = [$equipe->getEmail(),$equipe->getName()];
      }

      return $realEntities;
  }

   
}
