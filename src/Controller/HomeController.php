<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class HomeController extends AbstractController
{
    #[Route('', name: 'app_home')]
    public function index(RecipeRepository $RecipeRepository): Response
    {
        $recipe = $RecipeRepository->findAll();
        return $this->render('home/index.html.twig', [
            'recipe' => $recipe,
        ]);
    }
}
