<?php
namespace App\Controller;

use App\Repository\AnimalRepository;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends BaseController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(AnimalRepository $repository)
    {
        $animals = $repository->findAllPublishedOrderedByAnimals(5);

        return $this->_render('home/_base.html.twig', [
            'animals' => $animals,
            'title' => 'Fundacja Zwierzęta Niczyje'
        ]);
    }

    /**
     * @Route("/wizyta-przedadopcyjna", name="app_other_visit")
     */
    public function visit()
    {
        return $this->_render('other/visit.html.twig', [
            // 'animals' => $animals,
            'title' => 'Wizyta przedadopcyjna'
        ]);
    }

    /**
     * @Route("/umowa-przedadopcyjna", name="app_other_contract")
     */
    public function contract()
    {
        return $this->_render('other/contract.html.twig', [
            // 'animals' => $animals,
            'title' => 'Umowa przedadopcyjna'
        ]);
    }


    /**
     * @Route("/domy-tymczasowe", name="app_other_houses")
     */
    public function houses()
    {
        return $this->_render('other/houses.html.twig', [
            // 'animals' => $animals,
            'title' => 'Domy tymczasowe'
        ]);
    }


    /**
     * @Route("/kontakt", name="app_other_contact")
     */
    public function contact()
    {
        return $this->_render('other/contact.html.twig', [
            // 'animals' => $animals,
            'title' => 'Kontakt'
        ]);
    }
}