<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Recipe;
use App\Entity\Difficult;
use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                "label" => "Nom de la recette",
                "required" => true,
            ])
            ->add('duration', NumberType::class, [
                "label" => "Durée",
                "required" => true,
            ])
            ->add('nbPeople', NumberType::class, [
                "label" => "Nombre de personne",
                "required" => true,
            ])
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('updateAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('content', TextareaType::class, [
                "label" => "Détail de la recette",
                "required" => true,
            ])
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'multiple' => true,
                'required' => true,
            ])

            ->add('image', TextType::class, [
                "label" => "Ajouter l'url de l'image",
                "required" => true,
            ])

            ->add('difficult', EntityType::class, [
                'class' => Difficult::class,
                'choice_label' => 'name',
                'required' => true,
            ])
            // ->add('author', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'email',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
