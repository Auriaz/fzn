<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN_DASHBOARD")
 */
class DashboardAdminController extends AbstractController
{

    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index()
    {
        // $articleContent = $markdownHelper->parse($articleContent);
        return $this->render('dashboard_admin/index.html.twig', [
            
        ]);
    }
}
