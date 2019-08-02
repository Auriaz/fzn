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
        $articles = $repository->findAllPublishedOrderedByArticles(5);

        return $this->render('home/_base.html.twig', [
            'articles' => $articles,
            'title' => 'Fundacja Zwierzęta Niczyje'
        ]);
    }
}