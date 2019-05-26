<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class DashboardController extends BaseController
{

    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index()
    {
        // $articleContent = $markdownHelper->parse($articleContent);
        return $this->render('dashboard/index.html.twig', [
            
        ]);
    }

}
