<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Recipe;
use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('updateAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('content', TextareaType::class, [
                "label" => "Contenu du commentaire",
                "required" => true,
            ]);

        // ->add('user', EntityType::class, [
        // 'class' => User::class,
        // 'choice_label' => 'id',
        // ])
        // ->add('recipe', EntityType::class, [
        //     'class' => Recipe::class,
        //     'choice_label' => 'title',
        //     'required' => true,
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
