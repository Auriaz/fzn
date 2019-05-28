<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ArticleFormType extends AbstractType
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'help' => 'Wybierz coś chwytliwego!',
                'attr' => [
                    'placeholder' => 'Podaj tytuł',
                ]
            ])
            ->add('content', null, [
                'label' => 'Kontent',
                
            ])
            ->add('publishedAt', null, [
                'label' => 'Data',
                'widget' => 'single_text',
            ])
            ->add('author', EntityType::class, [
                'label' => 'Autor',
                'class' => User::class,
                'choice_label' => function(User $user) {
                    return sprintf('(%d) %s', $user->getId(), $user->getFirstName());
                },
                'placeholder' => 'Wybierz autora',
                'choices' => $this->userRepository->findAllEmailAlphabetical(),
                'invalid_message' => 'Wybierz poprawnie autora'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class
        ]);
    }
}