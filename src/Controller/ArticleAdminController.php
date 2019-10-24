<?php
namespace App\Controller;

use App\Entity\Article;
use App\FileManager\PhotoFileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\ArticleFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\PaginatorInterface;

class ArticleAdminController extends BaseController
{
    /**
     * @Route("/admin/article/new", name="admin_article_new")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new(EntityManagerInterface $em, Request $request, PhotoFileManager $uploaderHelper)
    {   
        $form = $this->createForm(ArticleFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $uploadedFile = $form['imageFile']->getData();

            if ($uploadedFile) {
                $newFilename = $uploaderHelper->uploadArticleImage( $uploadedFile, $article->getImageFilename());
                $article->setImageFilename($newFilename);
            }

            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Artykuł został dodany!');
            return $this->redirectToRoute('admin_article_list');
        }
       
        return $this->_render('article_admin/new.html.twig', [
            'articleForm' => $form->createView(),
            'title' => 'Artykuły'
        ]);
    }

    /**
     * @Route("/admin/article/{id}/edit", name="admin_article_edit")
     * @IsGranted("MANAGE", subject="article")
     */
    public function edit(Article $article, EntityManagerInterface $em, Request $request, PhotoFileManager $uploaderHelper)
    {
        $form = $this->createForm(ArticleFormType::class, $article, [
            'include_published_at' => true
        ]);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form[ 'imageFile']->getData();
            // dd( $form);
            if ($uploadedFile) {
               $newFilename = $uploaderHelper->uploadArticleImage($uploadedFile, $article->getImageFilename());
                $article->setImageFilename($newFilename);
            }

            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'Artykuł został zaktualizowany!');
            return $this->redirectToRoute( 'admin_article_list', [
                'id' => $article->getId(),
            ]);
        }
    
        return $this->_render('article_admin/edit.html.twig', [
            'articleForm' => $form->createView(),
            'article' => $article,
            'title' => 'Artykuły'
        ]);
    }

    /**
     * @Route("/admin/article", name="admin_article_list")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function list(ArticleRepository $articleRepository, PaginatorInterface $paginator)
    {
        $query = $this->request->query->get('query');

        $queryBuilder = $articleRepository->getWithSearchQueryBuilder($query);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $this->request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->_render('article_admin/list.html.twig', [
            'pagination' => $pagination,
            'title' => 'Artykuły'
        ]);
    }

    /**
     * @Route("/admin/article/{id}/delete", name="admin_article_delete")
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function delete($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);
        $article->setIsDelete(true);

        $this->em->persist($article);
        $this->em->flush();

        $this->addFlash('success', 'Artykuł został usuniety!');
        return $this->redirectToRoute('admin_article_list');
    }
}
