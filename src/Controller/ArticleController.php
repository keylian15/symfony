<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    #[Route('/article/creer', name: 'app_article_creer')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $entityManager->persist($article);
            $entityManager->flush();
        }

        return $this->render('article/creer.html.twig', [
            'controller_name' => 'ArticleController',
            'titre' => 'Article',
            'article' => $article,
            'form' => $form
        ]);
    }

    #[Route('/article/liste', name: 'app_article_liste')]
    public function liste(EntityManagerInterface $entityManager): Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();

        return $this->render('article/liste.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route('/article/update/{id}', name: 'app_article_update')]
    public function update(EntityManagerInterface $entityManager, int $id, Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');


        if (!$id) {
            throw $this->createNotFoundException(
                'Aucun article trouvé pour l\'ID ' . $id
            );
        }

        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'Aucun article trouvé pour l\'ID ' . $id
            );
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Article modifier !');

            return $this->redirectToRoute('app_article_liste');
        }

        return $this->render('article/creer.html.twig', [
            'controller_name' => 'ArticleController',
            'titre' => 'Article',
            'article' => $article,
            'form' => $form->createView()
        ]);
    }


    #[Route('/article/delete/{id}', name: 'app_article_delete')]
    public function delete(EntityManagerInterface $entityManager, int $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $article = $entityManager->getRepository(Article::class)->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'Aucun article trouvé pour l\'ID ' . $id
            );
        }

        $entityManager->remove($article);
        $entityManager->flush();
        return $this->redirectToRoute('app_article_liste');
    }
}
