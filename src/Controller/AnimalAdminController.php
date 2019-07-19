<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AnimalAdminController extends AbstractController
{
    /**
     * @Route("/animal/admin", name="animal_admin")
     */
    public function index()
    {
        return $this->render('animal_admin/index.html.twig', [
            'controller_name' => 'AnimalAdminController',
        ]);
    }
}
