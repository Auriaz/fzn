<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnimalAddFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name', TextType::class, [
                'label' => 'Imię zwierzęcia',
                'attr' => [
                    'placeholder' => 'Podaj imię',
                ]
            ])
            ->add('adoptionAt', null, [
                'label' => 'Tekst',
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Wybierz kategorie',
                'placeholder' => 'Wybierz kategorie',
                'choices' => [
                    'Kot' => 1,
                    'Pies' => 2,
                    'Inne zwierzęta' => 3,
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Opis',
            ]);

        // $imageConstraints = [
        //     new Image([
        //         'maxSize' => '5M'
        //     ]),
        // ];

        // if (!$isEdit || !$animal->getImage()) {
        //     $imageConstraints[] = new NotNull([
        //         'message' => 'Dodaj zdjęcię!',
        //     ]);
        // }

        $builder->add('image', FileType::class, [
            'mapped' => false,
            'required' => false,
            // 'constraints' => new NotNull([
            //     'message' => 'Dodaj zdjęcię!',
            // ]),
            // 'attr' => [
            //     'placeholder' => 'Podaj tytuł',
            // ],
        ]);


        // if ($options['include_published_at']) {
        //     $builder->add('publishedAt', null, [
        //         'label' => 'Data',
        //         'widget' => 'single_text',
        //     ]);
        // }
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
            'include_published_at' => false,
        ]);
    }
}
