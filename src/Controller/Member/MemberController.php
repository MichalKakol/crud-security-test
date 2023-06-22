<?php

namespace App\Controller\Member;

use App\Repository\DierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MemberController extends AbstractController
{
    #[Route('/member', name: 'member')]
    public function member(DierRepository $dierRepository): Response
    {
        $userName = $this->getUser()->getFname();
        return $this->render('member/member.html.twig', [
            'userNName' => $userName,
            'diers' => $dierRepository->findAll(),
        ]);
    }
}