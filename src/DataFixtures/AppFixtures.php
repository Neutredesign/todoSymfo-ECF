<?php

namespace App\DataFixtures;

<<<<<<< HEAD
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

=======
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail('admin@example.com');

        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');
        $user->setPassword($hashedPassword);

        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
>>>>>>> 78136c9 (Upload projet ECF Symfony)
        $manager->flush();
    }
}
