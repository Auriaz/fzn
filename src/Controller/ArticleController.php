<?php
namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ArticleRepository;

class ArticleController extends AbstractController
{
    /**
     * Currently unused: just showing a controller with a constructor!
     */
    private $isDebug;

    public function __construct(bool $isDebug)
    {
        $this->isDebug = $isDebug;
    }

    /**
     * @Route("/article/{slug}", name="article_show")
     */
    public function show(Article $article)
    {
        // $articleContent = $markdownHelper->parse($articleContent);
        return $this->render('article/show.html.twig', [
            'article' => $article,
            'title' => $article->getTitle()
        ]);
    }

    /**
     * @Route("/article/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart(Article $article, LoggerInterface $logger, EntityManagerInterface $em)
    {
        $article->incrementHeartCount();
        $em->flush();
        // TODO - actually heart/unheart the article!

        $logger->info('Article is being hearted');
        return new JsonResponse(['hearts' => $article->getHeartCount()]);
    }

    /**
     * @Route("/article", name="articles_show")
     */
    public function showArticles(ArticleRepository $repository)
    {
        $articles = $repository->findAllPublishedOrderedByArticles();

        return $this->render('article/showArticles.html.twig', [
            'articles' => $articles,
            'title' => 'Artykuły'
        ]);
    }
}
