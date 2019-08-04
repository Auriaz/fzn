<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FileManagerRepository;
use App\FileManager\PhotoFileManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileManagerController extends BaseController
{
    /**
     * @Route("/api/file-manager", name="file_manager")
     */
    public function index(FileManagerRepository $fileManagerRepository)
    {
        return $this->render('file_manager/index.html.twig', [
            'manager' => $fileManagerRepository->findByAll(),
            'title' => 'Menadżer plików',
        ]);
    }

    /**
     * @Route("/api/file-manager", methods="GET")
     */
    public function list(ImagePostRepository $repository)
    {
        $posts = $repository->findBy([], ['createdAt' => 'DESC']);

        return $this->toJson([
            'items' => $posts
        ]);
    }

    /**
     * @Route("/api/file-manager", methods="POST")
     */
    public function create(Request $request, ValidatorInterface $validator, PhotoFileManager $photoManager)
    {
        /** @var UploadedFile $imageFile */
        $imageFile = $request->files->get('file');

        $errors = $validator->validate($imageFile, [
            new Image(),
            new NotBlank()
        ]);

        if (count($errors) > 0) {
            return $this->toJson($errors, 400);
        }

        $newFilename = $photoManager->uploadImage($imageFile);
        $imagePost = new ImagePost();
        $imagePost->setFilename($newFilename);
        $imagePost->setOriginalFilename($imageFile->getClientOriginalName());

        $this->em->persist($imagePost);
        $this->em->flush();

        return $this->toJson($imagePost, 201);
    }

    /**
     * @Route("/api/file-manager/{id}", methods="DELETE")
     */
    public function delete(ImagePost $imagePost, EntityManagerInterface $entityManager, PhotoFileManager $photoManager)
    {
        $photoManager->deleteImage($imagePost->getFilename());

        $entityManager->remove($imagePost);
        $entityManager->flush();

        return new Response(null, 204);
    }

    /**
     * @Route("/api/file-manager/{id}", methods="GET", name="get_file_manager_post_item")
     */
    public function getItem(ImagePost $imagePost)
    {
        return $this->toJson($imagePost);
    }

    private function toJson($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        // add the image:output group by default
        if (!isset($context['groups'])) {
            $context['groups'] = ['image:output'];
        }

        return $this->json($data, $status, $headers, $context);
    }
}
