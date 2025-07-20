<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'article',
                'attr' => [
                    'placehorder' => 'Titre de l\'article'
                ]

            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug de votre article',
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie de l\'article',
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu de votre article',
            ])
            ->add('image', TextType::class, [
                'label' => 'Image de votre article',
            ])
            ->add('user', EntityType::class, [
                'label' => 'Utilisateur de l\'article',
                'class' => User::class,
                'choice_label' => 'firstname',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer l\'article',
                'attr' => [
                    'class' => 'w-100 mt-4 btn btn-dark'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
