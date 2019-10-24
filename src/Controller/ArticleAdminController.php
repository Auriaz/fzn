<?php
namespace App\Controller;

use App\Entity\Article;
use App\Entity\FileManager;
use App\FileManager\PhotoFileManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\ArticleFormType;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ArticleRepository;
use App\Repository\FileManagerRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gedmo\Sluggable\Util\Urlizer;
use App\Service\UploaderHelper;
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

    /**
     * @Route("/admin/article/{id}/attachment", name="admin_article_attachment")
     */
    public function uploadAttachment(Article $article,  FileManagerRepository $fileManagerRepository, PhotoFileManager $uploader)
    {
        $uploadedFile = $this->request->files->get('file');
        if($uploadedFile) {
            $file = $fileManagerRepository->findBy(['originalFilename' => $uploadedFile->getClientOriginalName()]);

            if (!empty($file)) {
                $errors = 'Odmowa! Już istnieje plik o tej nazwie!';

                return $this->json($errors, 400);
            }

            $filename = $uploader->uploadArticleReference($uploadedFile);
            $fileManager = new FileManager();
            $fileManager->setFilename($filename);
            $fileManager->setOriginalFilename($uploadedFile->getClientOriginalName() ?? $filename);
            $fileManager->setMimeType($uploadedFile->getMimeType() ?? 'application/octet-stream');
            $fileManager->setIsPublished(true);
            $fileManager->getArticles($article);

            $this->em->persist($fileManager);
            $this->em->flush();

            return $this->json(
                $article,
                201,
                [],
                [
                    'groups' => ['main']
                ]
            );
        }

        return $this->redirectToRoute('admin_article_edit');
    }
}
