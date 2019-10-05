<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalAddFormType;
use App\Repository\AnimalRepository;
use App\Service\UploaderHelper;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AnimalAdminController extends BaseController
{
    /**
     * @Route("/admin/animal/list", name="admin_animal_list")
     */
    public function list(AnimalRepository $animalRepository, PaginatorInterface $paginator)
    {
        $query = $this->request->query->get('query');

        $queryBuilder = $animalRepository->getWithSearchQueryBuilder($query);

        $pagination = $paginator->paginate(
            $queryBuilder, /* query NOT result */
            $this->request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        // dd($animals);
        return  $this->_render('animal_admin/list.html.twig',[
            'pagination' => $pagination,
            'title' => 'Zwierzęta'
        ]);
    }

    /**
     * @Route("/admin/animal/new", name="admin_animal_new")
     * @IsGranted("ROLE_ADMIN_ANIMAL")
     */
    public function new(UploaderHelper $uploaderHelper)
    {
        $form = $this->createForm(AnimalAddFormType::class);
        $form->handleRequest($this->request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->isValid());
            $animal = $form->getData();

            $uploadedFile = $form['imageFile']->getData();

            if ($uploadedFile) {
                $newFilename = $uploaderHelper->uploadArticleImage($uploadedFile, $animal->getImageFilename());
                $animal->setImageFilename($newFilename);
            }

            $this->em->persist($animal);
            $this->em->flush();

            $this->addFlash('success', 'Zwierzę zostało dodane!');
            return $this->redirectToRoute('admin_animal_list');
        }

        return $this->render('animal_admin/new.html.twig', [
            'animalForm' => $form->createView(),
            'title' => 'Zwierzęta'
        ]);
    }

    /**
     * @Route("/admin/animal/{id}/activity", name="admin_animal_activity" )
     */
    public function activity($id, AnimalRepository $animalRepository)
    {
        $animal = $animalRepository->find($id);
        if ($animal->getIsActive()) {
            $animal->setIsActive(false);
            $this->addFlash('success', sprintf('"%s" - Zmiena statusu na nieaktywny!', $animal->getName()));
        } else {
            $animal->setIsActive(true);
            $this->addFlash('success', sprintf('"%s" - Zmiena statusu na aktywny!',$animal->getName()));
        }

        $this->em->persist($animal);
        $this->em->flush();

        return $this->redirectToRoute('admin_animal_list', [
            'id' => $animal->getId(),
        ]);
    }

    /**
     * @Route("/admin/animal/{id}/category", name="admin_animal_change_category", methods="POST")
     */
    public function changeCategory($id, AnimalRepository $animalRepository)
    {
        $animal = $animalRepository->find($id);
        $newCategory = $this->request->request->get('category');
        // dd(intval($newCategory));
        $animal->setCategory(intval($newCategory));

        $this->em->flush();

        $category = '';
        switch ($newCategory) {
            case 1:
                $category = 'Kot';
                break;
            case 2:
                $category = 'Pies';
                break;
            default:
                $category = 'Inne zwierze';
                break;
        }

        $this->addFlash('success', sprintf('"%s" - Rodzaj został zmieniony na "' . $category . '" !', $animal->getName()));
        return $this->redirectToRoute('admin_animal_list', [
            'id' => $animal->getId(),
        ]);
    }

    /**
     * @Route("/admin/animal/{id}/edit", name="admin_animal_edit")
     */
    public function edit(Animal $animal, UploaderHelper $uploaderHelper)
    {
        $form = $this->createForm(AnimalAddFormType::class, $animal, [
            'include_published_at' => true
        ]);

        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form['imageFile']->getData();
            // dd( $form);
            if ($uploadedFile) {
                $newFilename = $uploaderHelper->uploadArticleImage($uploadedFile, $animal->getImageFilename());
                $animal->setImageFilename($newFilename);
            }

            $this->em->persist($animal);
            $this->em->flush();

            $this->addFlash('success', 'Zwierze zostało zaktualizowane!');
            return $this->redirectToRoute('admin_animal_list', [
                'id' => $animal->getId(),
            ]);
        }

        return $this->_render('article_admin/edit.html.twig', [
            'articleForm' => $form->createView(),
            'article' => $animal,
            'title' => 'Artykuły'
        ]);
    }

    /**
     * @Route("/admin/animal/{id}/delete", name="admin_animal_delete")
     * @IsGranted("ROLE_ADMIN_ANIMAL")
     */
    public function delete($id, AnimalRepository $animalRepository)
    {
        $animal = $animalRepository->find($id);
        $animal->setIsDelete(true);

        $this->em->persist($animal);
        $this->em->flush();

        $this->addFlash('success', 'Artykuł został usuniety!');
        return $this->redirectToRoute('admin_animal_list', [
                'id' => $animal->getId(),
            ]);
    }
}
