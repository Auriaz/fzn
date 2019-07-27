<?php

namespace App\Controller;

use App\Form\AnimalAddFormType;
use App\Repository\AnimalRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class AnimalAdminController extends BaseController
{
    /**
     * @Route("/admin/animal/list", name="admin_animal_list")
     */
    public function list(AnimalRepository $animals)
    {
        return  $this->_render('animal_admin/index.html.twig',[
            'animals' => $animals,
            'title' => 'Zwierzęta'
        ]);
    }

    /**
     * @Route("/admin/animal/new", name="admin_animal_new")
     * @IsGranted("ROLE_ADMIN_ANIMAL")
     */
    public function new()
    {
        $form = $this->createForm(AnimalAddFormType::class);
        $form->handleRequest($this->request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->isValid());
            $animal = $form->getData();

            // $uploadedFile = $form['imageFile']->getData();

            // if ($uploadedFile) {
            //     $newFilename = $uploaderHelper->uploadArticleImage($uploadedFile, $animal->getImageFilename());
            //     $animal->setImageFilename($newFilename);
            // }

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
}
