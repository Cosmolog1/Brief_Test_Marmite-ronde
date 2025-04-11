<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\RecipeRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class HomeController extends AbstractController
{
    #[Route('', name: 'app_home')]
    public function index(RecipeRepository $RecipeRepository, IngredientRepository $IngredientRepository): Response
    {
        $recipes = $RecipeRepository->findAll();
        $ingredients = $IngredientRepository->findAll();

        return $this->render('home/index.html.twig', [
            'recipes' => $recipes,
            'ingredients' => $ingredients,
        ]);
    }


    #[Route('/single/{id}', name: 'recipe_single')] //, requirements: ['id' => '\d+'])
    public function single(
        int $id,
        RecipeRepository $RecipeRepository,
        request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $recipe = $RecipeRepository->find($id);
        // dd($recipe);


        $comment = new Comment();
        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setRecipe($recipe);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('recipe_single', [
                'id' => $id
            ]);
        }


        return $this->render('home/single.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe
        ]);
    }
}
