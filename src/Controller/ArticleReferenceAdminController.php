<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\FileManager;
use App\FileManager\PhotoFileManager;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Repository\FileManagerRepository;
use App\Service\UploaderHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class ArticleReferenceAdminController extends BaseController
{
    /**
     * @Route("/admin/article/{id}/references", name="admin_article_add_reference", methods={"POST"})
     * @IsGranted("MANAGE", subject="article")
     */
    public function uploadArticleReference(Article $article,  FileManagerRepository $fileManagerRepository, PhotoFileManager $uploader, ValidatorInterface $validator, SerializerInterface $serializer)
    {
        if ($this->request->headers->get('Content-Type') === 'application/json') {
            /** @var ArticleReferenceUploadApiModel $uploadApiModel */
            $uploadApiModel = $serializer->deserialize(
                $this->request->getContent(),
                ArticleReferenceUploadApiModel::class,
                'json'
            );

            $violations = $validator->validate($uploadApiModel);
            if ($violations->count() > 0) {
                return $this->json($violations, 400);
            }

            $tmpPath = sys_get_temp_dir() . '/sf_upload' . uniqid();
            file_put_contents($tmpPath, $uploadApiModel->getDecodedData());
            $uploadedFile = new FileObject($tmpPath);
            $originalFilename = $uploadApiModel->filename;
        } else {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $this->request->files->get('file');
            $originalFilename = $uploadedFile->getClientOriginalName();
        }

        $errors = $validator->validate($uploadedFile, [
            new Image(),
            new NotBlank()
        ]);

        if($uploadedFile) {
            $file = $fileManagerRepository->findBy(['originalFilename' => $originalFilename]);

            if (!empty($file)) {
                $errors = 'Odmowa! Już istnieje plik o tej nazwie!';
                return $this->json(
                    $errors,
                    400
                );
            } 

            $filename = $uploader->uploadArticleReference($uploadedFile);

            $fileManager = new FileManager();
            $fileManager->setFilename($filename);
            $fileManager->setOriginalFilename($uploadedFile->getClientOriginalName() ?? $filename);
            $fileManager->setMimeType($uploadedFile->getMimeType() ?? 'application/octet-stream');
            $fileManager->setIsPublished(true);
            $fileManager->addArticle($article);

            $this->em->persist($fileManager);
            $this->em->flush();

            return $this->json(
                $fileManager,
                201,
                [],
                [
                    'groups' => ["article_reference:get"]
                ]
            );
        }

        return $this->redirectToRoute('admin_article_edit');
    }

    /**
     * @Route("/admin/article/{id}/references", methods={"GET"}, name="admin_article_list_references")
     */
    public function getArticleReferences(Article $article)
    {
       return $this->json(
           $article->getImages(),
            201,
            [],
            [
                'groups' => ["article_reference:get"]
            ]
        );
    }

    /**
     * @Route("/admin/article/{id}/references/reorder", methods="POST", name="admin_article_reorder_references")
     * @IsGranted("MANAGE", subject="article")
     */
    public function reorderArticleReferences(Article $article)
    {
        $orderedIds = json_decode($this->request->getContent(), true);

        if ($orderedIds === false) {
            return $this->json(['detail' => 'Invalid body'], 400);
        }

        // from (position)=>(id) to (id)=> (position)
        $orderedIds = array_flip($orderedIds);
        // foreach ($article->getImages() as $reference) {
        //     $reference->setPosition($orderedIds[$reference->getId()]);
        // }

        $this->em->flush();

        return $this->json(
            $article->getImages(),
            200,
            [],
            [
                'groups' => ["article_reference:put"]
            ]
        );
    }

    /**
     * @Route("/admin/article/references/{id}/download", name="admin_article_download_reference", methods={"GET"})
     * @IsGranted("MANAGE", subject="article")
     */
    public function downloadArticleReference(FileManager $fileManager, UploaderHelper $uploaderHelper)
    {
        $article = $fileManager->getArticles();

        try {
            $response = new BinaryFileResponse('uploads/'. $fileManager->getImagePath());

            $response->headers->set('Content-Type', 'application/pdf');
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $fileManager->getFilename());
            return $response;
        } catch (FileException $e) {
            throw new $e("Nie ma takiego pliku");
        }
        return $this->redirectToRoute('admin_article_edit');
    }

    /**
     * @Route("/admin/article/{idArticle}/references/{idImage}", name="admin_article_delete_reference", methods={"DELETE"})
     */
    public function deleteArticleReference($idArticle, $idImage, ArticleRepository $articleRepository, FileManagerRepository $fileManagerRepository)
    {
        $article = $articleRepository->find($idArticle);
        if (!$article) {
            throw $this->createNotFoundException('Nie znaloziono artykułu');
        }
        $image = $fileManagerRepository->find($idImage);
        if (!$image) {
            throw $this->createNotFoundException('Nie znaleziono zdięcia');
        }

        $article->removeImage($image);
        $this->em->persist($article);
        $this->em->flush();

        return new Response(null, 204);
    }

    /**
     * @Route("/admin/article/references/{id}", name="admin_article_update_reference", methods={"PUT"})
     * @IsGranted("MANAGE", subject="article")
     */
    public function updateArticleReference(Article $article, FileManager $fileManager, SerializerInterface $serializer,  ValidatorInterface $validator)
    {
        // $this->denyAccessUnlessGranted('MANAGE', $article);

        $serializer->deserialize(
            $this->request->getContent(),
            FileManager::class,
            'json',
            [
                'object_to_populate' => $fileManager,
                'groups' => ["article_reference:put"]
            ]
        );

        $violations = $validator->validate($fileManager);

        if ($violations->count() > 0) {
            return $this->json($violations, 400);
        }

        $this->em->persist($fileManager);
        $this->em->flush();

        return $this->json(
            $fileManager,
            200,
            [],
            [
                'groups' => ["article_reference:get"]
            ]
        );
    }
}