<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class AdminRecipeController extends AbstractController
{
    #[Route('/admin/recipe', name: 'app_admin_recipe')]
    public function index(RecipeRepository $RecipeRepository): Response
    {
        $recipe = $RecipeRepository->findAll();

        return $this->render('admin_recipe/index.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/admin/recipe/{id}', name: 'app_admin_recipe_show')]
    public function show($id, RecipeRepository $RecipeRepository): Response
    {
        $recipe = $RecipeRepository->find($id);

        return $this->render('admin_recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
    }

    #[Route('/admin/delete_recipe/{id}', name: 'app_admin_recipe_delete')]
    public function delete($id, RecipeRepository $RecipeRepository, EntityManagerInterface $entityManager): Response
    {
        $recipe = $RecipeRepository->find($id);

        $entityManager->remove($recipe);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_recipe');
    }

    #[Route('/admin/edit_recipe/{id}', name: 'app_admin_recipe_edit')]
    public function edit(
        $id,
        RecipeRepository $RecipeRepository,
        EntityManagerInterface $entityManager,
        Request $request,
    ): Response {

        $edit = $RecipeRepository->find($id);
        $form = $this->createForm(RecipeType::class, $edit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($edit);
            $entityManager->flush();
        }

        return $this->render("admin_recipe/edit.html.twig", parameters: [
            "edit" => $edit,
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/add_recipe', name: 'app_admin_recipe_add')]
    public function add_ing(
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        $rec = new Recipe();
        $form = $this->createForm(RecipeType::class, $rec);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($rec);
            $entityManager->flush();
        }



        return $this->render('admin_recipe/add.html.twig', [
            'controller_name' => 'AdminRecipeController',
            'form' => $form->createView(),

        ]);
    }
}
