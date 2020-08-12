<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\User\UserInterface;



class LoginController extends AbstractController
{
    /**
     * @Route("", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('login');
        // }
        $user=$this->getUser();
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error,'user'=>$user]);
    }

    /**
 * Redirect users after login based on the granted ROLE
 * @Route("/login/redirect", name="_login_redirect")
 */
public function loginRedirectAction(Request $request)
{
    if($this->isGranted('ROLE_COLLABORATEUR'))
    {
        return $this->redirectToRoute('home');
    }
    else if($this->isGranted('ROLE_ADMIN'))
    {
        return $this->redirectToRoute('home');
    }
    else
    {
        return $this->redirectToRoute('login');
    }
}

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
