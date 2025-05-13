<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class UserCoRecipeController extends AbstractController
{
    #[Route('/userco/recipe', name: 'app_userco_recipe')]
    public function index(RecipeRepository $RecipeRepository): Response
    {
        // L'id de l'utilisateur qui est connecté
        // $idUser = ????????

        $idUser = $this->getUser()->getId();
        $recipes = $RecipeRepository->findBy(['author' => $idUser]);

        return $this->render('userco_recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/userco/recipe/{id}', name: 'app_userco_recipe_show')]
    public function show($id, RecipeRepository $RecipeRepository): Response
    {
        $recipe = $RecipeRepository->find($id);

        return $this->render('userco_recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
    }

    #[Route('/userco/delete_recipe/{id}', name: 'app_userco_recipe_delete')]
    public function delete($id, RecipeRepository $RecipeRepository, EntityManagerInterface $entityManager): Response
    {
        $recipe = $RecipeRepository->find($id);

        $entityManager->remove($recipe);
        $entityManager->flush();

        return $this->redirectToRoute('app_userco_recipe');
    }

    #[Route('/userco/edit_recipe/{id}', name: 'app_userco_recipe_edit')]
    public function edit(
        $id,
        RecipeRepository $RecipeRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/upload/image')] string $imagesDirectory
    ): Response {

        $edit = $RecipeRepository->find($id);
        $form = $this->createForm(RecipeType::class, $edit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $imageFile->move($imagesDirectory, $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imageFilename' property to store the PDF file name
                // instead of its contents
                $edit->setImage($newFilename);
            }

            $entityManager->persist($edit);
            $entityManager->flush();
        }

        return $this->render("userco_recipe/edit.html.twig", parameters: [
            "edit" => $edit,
            "form" => $form->createView()
        ]);
    }

    #[Route('/userco/add_recipe', name: 'app_userco_recipe_add')]
    public function add_ing(
        EntityManagerInterface $entityManager,
        Request $request,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/upload/image')] string $imagesDirectory
    ): Response {
        $mailUser = $this->getUser()->getEmail();
        $rec = new Recipe();
        $rec->setAuthor($this->getUser());
        $form = $this->createForm(RecipeType::class, $rec);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                // Move the file to the directory where images are stored
                try {
                    $imageFile->move($imagesDirectory, $newFilename);
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imageFilename' property to store the PDF file name
                // instead of its contents
                $rec->setImage($newFilename);
            }

            $entityManager->persist($rec);
            $entityManager->flush();
        }



        return $this->render('userco_recipe/add.html.twig', [
            'controller_name' => 'UserCoRecipeController',
            'form' => $form->createView(),

        ]);
    }
}
