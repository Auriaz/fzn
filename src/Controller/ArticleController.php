<?php
namespace App\Controller;

use App\Entity\Article;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends BaseController
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
    public function show(Article $article, ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();      
        // $articleContent = $markdownHelper->parse($articleContent);
        return $this->render('article/show.html.twig', [
            'articles'=> $articles,
            'article' => $article,
            'title' => $article->getTitle()
            
        ]);
    }

    /**
     * @Route("/article", name="articles_show")
     */
    public function showArticles(ArticleRepository $articleRepository, PaginatorInterface $paginator, Request $request)
    {
        if(isset($request->query)) {
            $query = $request->query->get('query');
            $queryBuilder = $articleRepository->getWithSearchQueryBuilder($query);
        } else {
            $queryBuilder = $articleRepository->findAll();
        }
 
        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            8/*limit per page*/
        );

        return $this->render('article/index.html.twig', [
            'pagination' => $pagination,
            'title' => 'Artykuły'
        ]);
    }
}
