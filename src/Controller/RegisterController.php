<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\HttpFoundation\Response;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $user = new User;
       

        $form1 = $this->createForm(RegisterType::class, $user);


        // 2) handle the submit (will only happen on POST)
        $form1->handleRequest($request);
        if ($form1->isSubmitted() && $form1->isValid()) {

            $user->setRoles(["ROLE_COLLABORATEUR"]);
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);

            $imageFile = $form1['image']->getData();
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
                $user->setImage($newFilename);
                $user->setStatut('en attente');
                $user->setRoles([]);
                $this->addFlash('notice', 'Votre inscription est en cours de validation. ');
                
            }

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->render('register/index.html.twig', array('form1' => $form1->createView()));
    }
}
