<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\SectionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
/**
 * @IsGranted("ROLE_USER")
 */
class DashboardController extends BaseController
{
    public function __construct()
    {
     
    }

    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index(SectionRepository $section)
    {
    
        // $articleContent = $markdownHelper->parse($articleContent);
        return $this->_render('dashboard/index.html.twig', [
            'section' => $section,
            'title' => 'Strona kontrolna'
        ]);
    }
}
