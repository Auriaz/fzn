<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticleRepository;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(ArticleRepository $repository)
    {
        $articles = $repository->findAllPublishedOrderedByArticles();

        return $this->render('home/homepage.html.twig', [
            'articles' => $articles,
        ]);
    }
}