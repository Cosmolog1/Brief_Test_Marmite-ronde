<?php

namespace App\Controller;

use App\Entity\Difficult;
use App\Form\DifficultType;
use App\Repository\DifficultRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminDifficultController extends AbstractController
{
    #[Route('/admin/difficult', name: 'app_admin_difficult')]
    public function index(DifficultRepository $DifficultRepository): Response
    {
        $difficult = $DifficultRepository->findAll();

        return $this->render('admin_difficult/index.html.twig', [
            'difficult' => $difficult,
        ]);
    }

    #[Route('/admin/difficult/{id}', name: 'app_admin_difficult_show')]
    public function show($id, DifficultRepository $DifficultRepository): Response
    {
        $difficult = $DifficultRepository->find($id);

        return $this->render('admin_difficult/show.html.twig', [
            'difficult' => $difficult
        ]);
    }

    #[Route('/admin/delete_difficult/{id}', name: 'app_admin_difficult_delete')]
    public function delete($id, DifficultRepository $DifficultRepository, EntityManagerInterface $entityManager): Response
    {
        $difficult = $DifficultRepository->find($id);

        $entityManager->remove($difficult);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_difficult');
    }

    #[Route('/admin/edit_difficult/{id}', name: 'app_admin_difficult_edit')]
    public function edit(
        $id,
        DifficultRepository $DifficultRepository,
        EntityManagerInterface $entityManager,
        Request $request,
    ): Response {

        $edit = $DifficultRepository->find($id);
        $form = $this->createForm(DifficultType::class, $edit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($edit);
            $entityManager->flush();
        }

        return $this->render("admin_difficult/edit.html.twig", parameters: [
            "edit" => $edit,
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/add_difficult', name: 'app_admin_difficult_add')]
    public function add_ing(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $dif = new Difficult();
        $form = $this->createForm(DifficultType::class, $dif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($dif);
            $entityManager->flush();
        }



        return $this->render('admin_difficult/add.html.twig', [
            'controller_name' => 'AdminDifficultController',
            'form' => $form->createView(),

        ]);
    }
}
