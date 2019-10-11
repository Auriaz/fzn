<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Animal;
use App\Repository\AnimalRepository;
use Knp\Component\Pager\PaginatorInterface;

class AnimalController extends BaseController
{
    /**
     * @Route("/animal/{id}", name="animal_show")
     */
    public function show(Animal $animal, AnimalRepository $animalRepository)
    {
        $id = $animal->getId();
        $name = $animal->getName();
        $category = $animal->getCategory();
        $animals = $animalRepository->findAll();
        // $articleContent = $markdownHelper->parse($articleContent);
        return $this->render('animal/show.html.twig', [
            'animals' => $animals,
            'animal' => $animal,
            'title' => $name,
            'category' => $category,
            'id' => $id
        ]);
    }
    /**
     * @Route("/animal-{category}", name="animal_show_category")
     */
    public function index($category = null, AnimalRepository $animalRepository, PaginatorInterface $paginator)
    {
        $query = $this->request->query->get('query');

        switch ($category) {
            case 'koty':
                $queryBuilder = $animalRepository->getSearchQueryBuilderCategory(1, $query);
                $category = 'Koty';
                break;
            case 'psy':
                $queryBuilder = $animalRepository->getSearchQueryBuilderCategory(2, $query);
                $category = 'Psy';
                break;
            case 'inne':
                $queryBuilder = $animalRepository->getSearchQueryBuilderCategory(3, $query);
                $category = 'Inne zwierzęta';
                break;
            
            default:
                $queryBuilder = $animalRepository->getSearchQueryBuilderCategory(null, $query);
                $category = 'Wszystkie zwierzęta';
                break;
        }

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $this->request->query->getInt('page', 1)/*page number*/,
            8/*limit per page*/
        );

        return $this->_render('animal/index.html.twig', [
            'pagination' => $pagination,
            'category' => $category,
            'title' => $category
        ]);
    }
}
