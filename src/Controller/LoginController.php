<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class LoginController extends AbstractController
{
    #[Route('/login', name: 'home_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
    #[Route('/redirect', name: 'redirect')]
    public function redirectAction(Security $security): Response
    {
        if($security->isGranted('ROLE_ADMIN')) {
            $admin = $this->getUser();
            $adminName = $admin->getFname();
            $adminLand = $admin->getPart();
            return $this->redirectToRoute('admin', [
                'name' => $adminName,
                'land' => $adminLand,
            ]);
        }
        if($security->isGranted('ROLE_MEMBER')) {
            $user = $this->getUser();
            $userName = $user->getFname();
            return $this->redirectToRoute('member',[
                'name' => $userName,
            ]);
        }
        return $this->redirectToRoute('home');
    }
}
