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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final class AdminRecipeController extends AbstractController
{
    #[Route('/admin/recipe', name: 'app_admin_recipe')]
    public function index(RecipeRepository $RecipeRepository): Response
    {
        $recipes = $RecipeRepository->findAll();

        return $this->render('admin_recipe/index.html.twig', [
            'recipes' => $recipes,
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

        return $this->render("admin_recipe/edit.html.twig", parameters: [
            "edit" => $edit,
            "form" => $form->createView()
        ]);
    }

    #[Route('/admin/add_recipe', name: 'app_admin_recipe_add')]
    public function add_ing(
        EntityManagerInterface $entityManager,
        Request $request,
        SluggerInterface $slugger,
        #[Autowire('%kernel.project_dir%/public/upload/image')] string $imagesDirectory
    ): Response {
        $rec = new Recipe();
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


        return $this->render('admin_recipe/add.html.twig', [
            'controller_name' => 'AdminRecipeController',
            'form' => $form->createView(),

        ]);
    }
}
