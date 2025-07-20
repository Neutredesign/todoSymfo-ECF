<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        # Création d'un utilisateur
        $user = new User();
        $user->setFirstname('Hugo')
            ->setLastname('LIEGEARD')
            ->setEmail('hugo@actu.news')
            ->setPassword('demo')
            ->setRoles(['ROLE_ADMIN']);

        # Enregistrement de l'utilisateur
        $manager->persist($user);

        # Création des catégories
        $categories = ['Politique', 'Economie', 'Culture', 'Sport', 'Loisirs'];
        $categoriesDb = [];
        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $category->setSlug(strtolower(str_replace(' ', '-', $categoryName)));
            $manager->persist($category);
            $categoriesDb[] = $category;
        }
        
        # Création des articles
        for ($i = 1; $i <= 50; $i++) {
            $post = new Post();
            $post->setTitle('Titre de l\'article ' . $i)
                ->setContent('Contenu de l\'article ' . $i)
                ->setImage('https://placehold.co/600x400')
                ->setSlug('titre-de-l-article-' . $i)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTimeImmutable())
                ->setCategory($categoriesDb[array_rand($categoriesDb)])
                ->setUser($user);

            $manager->persist($post);
        }

        $manager->flush();
    }
}
