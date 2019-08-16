<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(ArticleRepository $repository)
    {
        $articles = $repository->findAllPublishedOrderedByArticles(5);

        return $this->_render('home/_base.html.twig', [
            'articles' => $articles,
            'title' => 'Fundacja Zwierzęta Niczyje'
        ]);
    }
}