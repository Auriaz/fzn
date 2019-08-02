<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use App\Form\UserSelectTextType;
use App\Repository\UserRepository;
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

class ArticleFormType extends AbstractType
{
    private $userRepository;
    private $router;

    public function __construct(UserRepository $userRepository, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $article = $options['data'] ?? null;
        $isEdit = $article && $article->getId();

        $builder
            ->add('title', TextType::class, [
                // 'help' => 'Wybierz coś chwytliwego!',
                'label' => 'Tytuł', 
                'attr' => [
                    'placeholder' => 'Podaj tytuł',
                ]
            ])
            ->add('content', null, [
                'label' => 'Tekst',          
            ])
            ->add('author', UserSelectTextType::class, [
                'label' => 'Autor', 
                'disabled' => $isEdit
            ])
            ->add('location', ChoiceType::class, [
                'label' => 'Wybierz miejsce',
                'placeholder' => 'Wybierz lokacje',
                'choices' => [
                    'The Solar system' => 'solar_system',
                    'Near a star' => 'star',
                    'Interstellar Space' => 'interstellar_space',
                ],
                'attr' => [
                    'data-specific-location-url'=>$this->router->generate('admin_article_location_select'),
                    'class' => 'article-form-location'
                ],
                'required' => false,
            ]);

        $imageConstraints = [
            new Image([
                'maxSize' => '5M'
            ]),
        ];

        if (!$isEdit || !$article->getImageFilename()) {
            $imageConstraints[] = new NotNull([
                'message' => 'Dodaj zdjęcię!',
            ]);
        }

        $builder->add('imageFile', FileType::class, [
            'mapped' => false,
            'required' => false,
            'constraints' => $imageConstraints, 
            'attr' => [
                'placeholder' => 'Podaj tytuł',
            ],
        ]);
    
        if($options['include_published_at']) {
            $builder->add('publishedAt', null, [
                'label' => 'Data',
                'widget' => 'single_text',
            ]);    
        }

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var Article|null $data */
                $data = $event->getData();
                if (!$data) {
                    return;
                }
                $this->setupSpecificLocationNameField(
                    $event->getForm(),
                    $data->getLocation()
                );
            }
        );

        $builder->get('location')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->setupSpecificLocationNameField(
                    $form->getParent(),
                    $form->getData()
                );
            }
        );
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'include_published_at' => false,
        ]);
    }

    private function getLocationNameChoices(string $location)
    {
        $planets = [
            'Mercury',
            'Venus',
            'Earth',
            'Mars',
            'Jupiter',
            'Saturn',
            'Uranus',
            'Neptune',
        ];

        $stars = [
            'Polaris',
            'Sirius',
            'Alpha Centauari A',
            'Alpha Centauari B',
            'Betelgeuse',
            'Rigel',
            'Other'
        ];

        $locationNameChoices = [
            'solar_system' => array_combine($planets, $planets),
            'star' => array_combine($stars, $stars),
            'interstellar_space' => null,
        ];

        return $locationNameChoices[$location] ?? null;
    }

    private function setupSpecificLocationNameField(FormInterface $form, ?string $location)
    {
        if (null === $location) {
            $form->remove('specificLocationName');
            return;
        }
        $choices = $this->getLocationNameChoices($location);
        if (null === $choices) {
            $form->remove('specificLocationName');
            return;
        }
        $form->add('specificLocationName', ChoiceType::class, [
            'placeholder' => 'Where exactly?',
            'choices' => $choices,
            'required' => false,
        ]);
    }
}