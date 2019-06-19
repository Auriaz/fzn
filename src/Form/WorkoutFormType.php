<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Routing\RouterInterface;


class WorkoutFormType extends AbstractType
{
    // private $userRepository;
    // private $router;

    // public function __construct(UserRepository $userRepository, RouterInterface $router)
    // {
    //     $this->userRepository = $userRepository;
    //     $this->router = $router;
    // }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // $article = $options['data'] ?? null;
        // $isEdit = $article && $article->getId();

        // $builder
        //     ->add('title', TextType::class, [
        //         // 'help' => 'Wybierz coś chwytliwego!',
        //         'label' => 'Tytuł',
        //         'attr' => [
        //             'placeholder' => 'Podaj tytuł',
        //         ]
        //     ]);
 
    }

    // public function configureOptions(OptionsResolver $resolver)
    // {
        // $resolver->setDefaults([
        //     // 'data_class' => section::class,
        //     // 'include_published_at' => false,
        // ]);
    // }
}
