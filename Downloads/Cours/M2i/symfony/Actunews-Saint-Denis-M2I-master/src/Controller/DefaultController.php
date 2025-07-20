<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default_home', methods: ['GET'])]
    public function home(PostRepository $postRepository): Response
    {
        # Récupérer les derniers articles (Contact du model)
        $posts = $postRepository->findBy([], ['createdAt' => 'DESC']);

        # Transmettre les données à la vue
        return $this->render('default/home.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/categorie/{slug}', name: 'default_category', methods: ['GET'])]
    # https://localhost:8000/politique
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy(['slug' => $slug]);

        return $this->render('default/category.html.twig', [
            'category' => $category,
        ]);

        # return new Response("<h1>Catégorie : $slug</h1>");
    }

    #[Route('/{categorySlug}/{postSlug}_{id}.html', name: 'default_post', methods: ['GET'])]
    # https://localhost:8000/politique/bientot-de-nouvelles-elections-municipales_45768.html
    public function post($categorySlug, $postSlug, $id, PostRepository $postRepository): Response
    {
        # Recuperation de l'article via l'id dans l'URL
        $post = $postRepository->find($id); # Ahlem ✔

        # Transmettre les données à la vue
        return $this->render('default/post.html.twig', [
            'post' => $post,
        ]);
    }
}