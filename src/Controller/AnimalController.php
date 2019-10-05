<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Animal;
use App\Repository\AnimalRepository;

class AnimalController extends BaseController
{
    /**
     * @Route("/animal/{slug}", name="animal_show")
     */
    public function show(Animal $animal, AnimalRepository $animalRepository)
    {
        $animals = $animalRepository->findAll();
        // $articleContent = $markdownHelper->parse($articleContent);
        return $this->render('article/show.html.twig', [
            'articles' => $animals,
            'article' => $animal,
            'title' => $animal->getTitle()
        ]);
    }
    // /**
    //  * @Route("/animal-{category}", name="animal_show")
    //  */
    // public function index($category)
    // {
    //     switch ($category) {
    //         case '1':
    //             $animals = $this->em->getRepository(Animal::class)->findBy(['category' => $category]);
    //             $category = 'Koty';
    //             break;
    //         case '2':
    //             $animals = $this->em->getRepository(Animal::class)->findBy(['category' => $category]);
    //             $category = 'Psy';
    //             break;
    //         case '3':
    //             $animals = $this->em->getRepository(Animal::class)->findBy(['category' => $category]);
    //             $category = 'Inne zwierzęta';
    //             break;
            
    //         default:
    //             $animals = $this->em->getRepository(Animal::class)->findAll();
    //             $category = 'Wszystkie zwierzęta';
    //             break;
    //     }

    //     return $this->_render('animal/index.html.twig', [
    //         'animals' => $animals,
    //         'category' => $category
    //     ]);
    // }
}
