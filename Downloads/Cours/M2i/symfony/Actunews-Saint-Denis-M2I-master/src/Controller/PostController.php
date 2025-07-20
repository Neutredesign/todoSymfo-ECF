<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('/rediger-un-article', name: 'post_create', methods: ['GET', 'POST'])]
    public function createPost(Request                $request,
                               EntityManagerInterface $entityManager): Response
    {
        # Création d'un nouvel article
        $post = new Post();
        $post->setCreatedAt(new \DateTimeImmutable());
        $post->setUpdatedAt(new \DateTimeImmutable());

        # Récupérer le formulaire
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        # Vérifier si le formulaire est soumis
        if ($form->isSubmitted()) {

            # Insertion de l'article dans la base
            $entityManager->persist($post);
            $entityManager->flush();

            # Redirection vers la page de l'article
            return $this->redirectToRoute('default_post', [
                'categorySlug' => $post->getCategory()->getSlug(),
                'postSlug' => $post->getSlug(),
                'id' => $post->getId()
            ]);

        }

        # Affichage du formulaire
        return $this->render('post/create.html.twig', [
            'form' => $form
        ]);

    }
}