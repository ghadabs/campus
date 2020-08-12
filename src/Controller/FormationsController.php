<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Formation;
use App\Entity\User;
use App\Entity\Session;
use App\Entity\Equipe;
use App\Repository\FormationRepository;
use App\Repository\SessionRepository;
use App\Data\SearchData;
use App\Form\SearchHomeType;
// use App\Form\SessionType;
use App\Form\ProposerFormationType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
class FormationsController extends AbstractController
{
    /**
     * @Route("/formations/presentielles", name="presentielles")
     */
    public function présentielles(TokenStorageInterface $tokenStorage, FormationRepository $repository, Request $request)
    {
        $user = $tokenStorage->getToken()->getUser();
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchHomeType::class, $data);
        $form->handleRequest($request);
        $formations = $repository->findSearchP($data, '2','validée');
        return $this->render('formations/index.html.twig', [
            'formations' => $formations, 'user' => $user, 'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/formations/enLigne", name="enLigne")
     */
    public function enLigne(TokenStorageInterface $tokenStorage, FormationRepository $repository, Request $request)
    {
        $user = $tokenStorage->getToken()->getUser();
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchHomeType::class, $data);
        $form->handleRequest($request);
        $formations = $repository->findSearchP($data, '1', 'validée');
        return $this->render('formations/index.html.twig', [
            'formations' => $formations, 'user' => $user, 'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/proposer", name="proposer")
     */
    public function Proposer(TokenStorageInterface $tokenStorage, FormationRepository $repository,SessionRepository $reposit, Request $request)
    {
        
        $user = $tokenStorage->getToken()->getUser();
        $em = $this->getDoctrine()->getManager();
        $formation=new Formation();

 

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
        $file=$e->get('photo')->getData();
            
       if($file){
        $fichier = md5(uniqid()).'.'.$file->guessExtension();
        
        // On copie le fichier dans le dossier uploads
        $file->move(
            $this->getParameter('photo_directory'),
            $fichier
        );
        
        // On crée l'image dans la base de données
        
        $e->getData()->setPhoto($fichier);
    }else {$e->getData()->setPhoto('https://mauritanie-talent-innovation.org/toto.png');}
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

               //$formation=$form->getData();

            dump($form->get('sessions')->getData());
               $em->persist($formation);
               $em -> flush();
  
    return $this->redirectToRoute("proposer");  
        }
    
        return $this->render('formations/ProposerFormation.html.twig', [
             'user' => $user,'form' => $form->createView(),'formation'=>$formation
             
        ]);
    }


}
