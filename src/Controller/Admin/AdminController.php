<?php

namespace App\Controller\Admin;

use Symfony\Component\Security\Core\Security;
use App\Entity\Dier;
use App\Form\DierType;
use App\Repository\DierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/admin', name: 'admin')]
    public function admin(): Response
    {
        $admin = $this->getUser();
        $adminName = $admin->getFname();
        $adminLand = $admin->getPart();
        $diers = $admin->getDieren();

        return $this->render('admin/admin.html.twig', [
            'adminNName' => $adminName,
            'adminLLand' => $adminLand,
            'diers' => $diers,
        ]);
    }

    #[Route('/admin/new', name: 'admin_dier_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $admin = $this->getUser();
        $dier = new Dier();
        $dier->setUser($admin); // Set the logged-in admin as the user

        $form = $this->createForm(DierType::class, $dier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dier);
            $entityManager->flush();

            return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/new.html.twig', [
            'dier' => $dier,
            'form' => $form,
        ]);
    }
    #[Route('/admin/{id}', name: 'admin_dier_delete', methods: ['POST', 'GET'])]
    public function delete(Request $request, Dier $dier, DierRepository $dierRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dier->getId(), $request->request->get('_token'))) {
            $dierRepository->remove($dier, true);
        }

        return $this->redirectToRoute('admin', [], Response::HTTP_SEE_OTHER);
    }
}
#eddit
