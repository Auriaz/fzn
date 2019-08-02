<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FileManagerRepository;

class FileManagerController extends AbstractController
{
    /**
     * @Route("/file/manager", name="file_manager")
     */
    public function index(FileManagerRepository $fileManagerRepository)
    {
        return $this->render('file_manager/index.html.twig', [
            'manager' => $fileManagerRepository->findByAll(),
            'title' => 'Menadżer plików',
        ]);
    }

    /**
     * @Route("/file/manager/add-new-image", name="file_manager_add")
     */
    public function add(Type $var = null)
    {
        return $this->render('file_manager/add.html.twig', [
            'title' => 'Menadżer plików',
        ]);
    }
}
