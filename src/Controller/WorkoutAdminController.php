<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WorkoutAdminController extends AbstractController
{
    /**
     * @Route("/workout/admin", name="workout_admin")
     */
    public function index()
    {
        return $this->render('workout_admin/index.html.twig', [
            'controller_name' => 'WorkoutAdminController',
        ]);
    }
}
