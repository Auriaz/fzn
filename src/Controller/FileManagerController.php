<?php

namespace App\Controller;

use App\Entity\FileManager;
use App\FileManager\PhotoFileManager;
use App\Message\FileManager\AddPhotoToFileManager;
use App\Message\FileManager\DeletePhotoManager;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FileManagerRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Constraints\File;

/**
 * @IsGranted("ROLE_ADMIN_IMAGE")
 */
class FileManagerController extends BaseController
{
    /**
     * @Route("/file-manager",  name="file_manager")
     */
    public function Index()
    {
        return $this->_render('file_manager/index.html.twig', [
            'title' => 'Menager Zdjęć'
        ]);
    }

    /**
     * @Route("/api/files",  methods="GET", name="api_file_manager_get" )
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
    public function create($file = null, ValidatorInterface $validator, FileManagerRepository $file_manager_repository, MessageBusInterface $messageBusInterface)
    {
        if(empty($file)) {
            $file = $this->request->files->get('file');
        }

        $errors = $validator->validate($file, [
            // new File(),
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

        // TODO extension
        // $input = ['extension' => $file->guessExtension()];
        // $rules = ['extension' => 'in:jpeg,jpg,png,bmp,gif'];

        // $newFilename = $photo_manager->uploadImage($addPhotoToFileManager->getFile());
        // $orginalFilename = $file->getClientOriginalName();

        $file_manager = new FileManager();

        $message = new AddPhotoToFileManager($file, $file_manager);
        $messageBusInterface->dispatch($message);

        return $this->toJson($file_manager, 201);
    }

    /**
     * @Route("/api/files/{id}", methods="PUT")
     */
    public function active(FileManager $file_manager)
    {
        $description = $this->request->request->get('description');
        $isPublished = $this->request->request->get('isPublished');
        
        if(!empty($description)) {
             $file_manager->setDescription($description);
        } 

        if(!empty($isPublished)) {
            if($file_manager->getIsPublished() === true) {
                $file_manager->setIsPublished(false);
            } else {
                $file_manager->setIsPublished(true);
            }
        }

        $this->em->flush();
        
        return $this->toJson($file_manager, 201);
    }

    /**
     * @Route("/api/files/{id}/description", methods="POST")
     */
    public function setDescription(FileManager $file_manager)
    {
        $file = $this->request->request->get('description');

        $file_manager->setDescription($file);
        $this->em->flush();

        return $this->toJson($file_manager, 201);
    }

    /**
     * @Route("/api/files/{id}", methods="DELETE")
     */
    public function delete(FileManager $file_manager, MessageBusInterface $messageBusInterface)
    {
        $messageBusInterface->dispatch(new DeletePhotoManager($file_manager));

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
