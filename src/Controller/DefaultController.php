<?php
namespace App\Controller;

use App\Repository\AnimalRepository;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\Stream;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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


    /**
     * @Route("/ankieta-{slug}", name="app_other_questionnaire")
     */
    public function questionnaire($slug)
    {
        
        // Retrieve the HTML generated in our twig file
        return $this->_render('other/questionnaire.html.twig', [
            'title' => "Ankieta " . $slug,
            'name_pdf' => 'ankieta_'. $slug
        ]);
    }

    /**
     * @Route("/download-pdf-{name_pdf}", name="app_other_download_pdf")
     */
    public function downloadPdf($name_pdf)
    {   
        $file = new Stream('documents/pdf/' . $name_pdf . '.pdf');

        try {
            $response = new BinaryFileResponse($file);

            $response->headers->set('Content-Type', 'application/pdf');
            $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $name_pdf.'.pdf'); 
            return $response;
        } catch (FileException $e) {
            throw new $e("Nie ma takiego pliku" );
        }
        
    }
}