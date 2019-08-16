<?php

namespace App\Controller;

use App\Entity\FileManager;
use App\FileManager\PhotoFileManager;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FileManagerRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;


class FileManagerController extends BaseController
{
    /**
     * @Route("/file-manager",  name="file_manager")
     */
    public function Index()
    {
        return $this->_render('file_manager/index.html.twig', [
            'title' => 'File Manager'
        ]);
    }

    /**
     * @Route("/api/files",  methods="GET")
     */
    public function list(FileManagerRepository $file_manager_repository)
    {
        $posts = $file_manager_repository->findBy([], ['createdAt' => 'DESC']);

        return $this->toJson([
            'items' => $posts
        ]);
    }

    /**
     * @Route("/api/files", methods="POST")
     */
    public function create( ValidatorInterface $validator, PhotoFileManager $photo_manager, FileManagerRepository $file_manager_repository)
    {
        $file = $this->request->files->get('file');
        $errors = $validator->validate($file, [
            new Image(),
            new NotBlank()
        ]);
       
        $files = $file_manager_repository->findBy(['originalFilename' => $file->getClientOriginalName()]);

        if (!empty($files)) {
            $errors = 'Odmowa! Już istnieje plik o tej nazwie!';

            return $this->toJson($errors, 400);
        }
            
        if (count($errors) > 0) {
            return $this->toJson($errors, 400);
        }

        $input = ['extension' => $file->guessExtension()];
        $rules = ['extension' => 'in:jpeg,jpg,png,bmp,gif'];

        // TODO extension
        $newFilename = $photo_manager->uploadImage($file);
        

        $file_manager = new FileManager();
        $file_manager->setFilename($newFilename);
        $file_manager->setOriginalFilename($file->getClientOriginalName());

        $this->em->persist($file_manager);
        $this->em->flush();

        return $this->toJson($file_manager, 201);
    }

    /**
     * @Route("/api/files/{id}", methods="DELETE")
     */
    public function delete(FileManager $file_manager, PhotoFileManager $photo_manager)
    {
        $photo_manager->deletePhoto($file_manager->getFilename());

        $this->em->remove($file_manager);
        $this->em->flush();

        return new Response(null, 204);
    }

    /**
     * @Route("/api/files/{id}", methods="GET", name="get_file_post_item")
     */
    public function getItem(FileManager $file_manager)
    {
        return $this->toJson($file_manager);
    }

    private function toJson($data, int $status = 200, array $headers = [], array $context = [])
    {
        // add the file_manager:output group by default
        if (!isset($context['groups'])) {
            $context['groups'] = ['files:output'];
        }

        return $this->json($data, $status, $headers, $context);
    }
}
