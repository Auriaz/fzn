<?php
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Knp\Component\Pager\PaginatorInterface;
/**
 * @method User getUser()
 */
abstract class BaseController extends AbstractController
{
    protected $conn;

    protected $router;

    protected $request;

    protected $session;

    protected $paginator;

    protected $security;

    protected $em;

    public function __construct(UrlGeneratorInterface $router, PaginatorInterface $paginator, Security $security, ContainerInterface $container = null) 
    {
        parent::setContainer($container);
        
        $this->security = $security;
        $this->router = $router;
        $this->paginator = $paginator;
        
        $this->request = Request::createFromGlobals();
        $this->conn = $this->getDoctrine()->getManager()->getConnection();
        $this->em = $this->getDoctrine()->getManager();
        $this->response = new Response();
        $this->session = new Session();
        
        $this->cookies = $this->request->cookies;
    }

    protected function _render($twig, array $variable = array())
    {

        $param = array();
        $param = $variable;
        // $param['imie'] = $this->user->getImieNazwisko();
        // $param['title'] = $this->page_settings->getPageTitle();
        // $param['wyloguj_link'] = $this->router->generate('app_logout');
        // $param['page_settings'] = array(
        //     'per_page' => $this->page_settings->per_page,
        //     'page' => $this->request->query->getInt('page', 1)
        // );

        $response = $this->render($twig, $param);

        return $response;
    }


    protected function _json(array $json = array())
    {
        return new JsonResponse($json);
    }
}
