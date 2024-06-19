<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Compound;
use App\Form\AddCompoundFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/compounds', name: 'app_compounds_list_')]
final class CompoundController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('compound/index.html.twig', [
            'controller_name' => 'CompoundController',
        ]);
    }

    #[Route('/add', name: 'add')]
    public function addCompound(
        Request $request,
        EntityManagerInterface $em,
        UserRepository $userRepository
    ): Response {
        $compound = new Compound();

        $form = $this->createForm(AddCompoundFormType::class, $compound);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $compound->setUser($userRepository->find(1));

            $em->persist($compound);
            $em->flush();

            $this->addFlash('success', 'A new compound has been added');

            return $this->redirectToRoute('app_compound_index');
        }

        return $this->render('compound/add.html.twig', [
            'form' => $form,
        ]);
    }
}
