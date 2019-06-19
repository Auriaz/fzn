<?php

namespace App\Controller;


use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 */
class WorkoutController extends BaseController
{
    public function __construct()
    {
        
    }

    /**
     * @Route("/dashboard/workout", name="dashboard_workout")
     */
    public function index()
    {
        return $this->render( 'workout/index.html.twig', [
            
        ]);
    }
}
