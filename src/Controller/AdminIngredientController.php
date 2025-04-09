<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminIngredientController extends AbstractController
{
    #[Route('/admin/ingredient', name: 'app_admin_ingredient')]
    public function index(IngredientRepository $IngredientRepository): Response
    {
        $ingredient = $IngredientRepository->findAll();

        return $this->render('admin_ingredient/index.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    #[Route('/admin/ingredient/{id}', name: 'app_admin_ingredient_show')]
    public function show($id, IngredientRepository $IngredientRepository): Response
    {
        $ingredient = $IngredientRepository->find($id);

        return $this->render('admin_ingredient/show.html.twig', [
            'ingredient' => $ingredient
        ]);
    }

    #[Route('/admin/delete_ingredient/{id}', name: 'app_admin_ingredient_delete')]
    public function delete($id, IngredientRepository $IngredientRepository, EntityManagerInterface $entityManager): Response
    {
        $ingredient = $IngredientRepository->find($id);

        $entityManager->remove($ingredient);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_ingredient');
    }

    #[Route('/admin/edit_ingredient/{id}', name: 'app_admin_ingredient_edit')]
    public function edit(
        $id,
        IngredientRepository $IngredientRepository,
        EntityManagerInterface $entityManager,
        Request $request,
    ): Response {

        $edit = $IngredientRepository->find($id);
        $form = $this->createForm(IngredientType::class, $edit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($edit);
            $entityManager->flush();
        }

        return $this->render("admin_ingredient/edit.html.twig", parameters: [
            "edit" => $edit,
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/add_ingredient', name: 'app_admin_ingredient_add')]
    public function add_ing(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $ing = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ing);
            $entityManager->flush();
        }



        return $this->render('admin_ingredient/add.html.twig', [
            'controller_name' => 'AdminIngredientController',
            'form' => $form->createView(),

        ]);
    }
}
