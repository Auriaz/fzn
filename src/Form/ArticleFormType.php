<?php

namespace App\Form;

use App\Entity\Article;
use App\Form\UserSelectTextType;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

        if($article) {
            if($article->getIsDelete() == true) {
                $isDelete = true;    
            } else {
                $isDelete = false;
            }
        } else {
            $isDelete = false;
        }

        if(!$isDelete) {
            $builder
                ->add('title', TextType::class, [
                    'label' => 'Tytuł',
                    'attr' => [
                        'placeholder' => 'Podaj tytuł',
                    ]
                ])
                ->add('content', null, [
                    'label' => 'Tekst',
                    'attr' => [
                        'class' => 'editor'
                    ],
                ])
                ->add('author', UserSelectTextType::class, [
                    'label' => 'Autor',
                    'disabled' => $isEdit
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

            if ($isEdit) {
                $filename = $article->getImageFilename();
            } else {
                $filename = 'Podaj tytuł';
            };

            $builder->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => $imageConstraints,
                'attr' => [
                    'placeholder' => $filename,
                ],
            ]);

            if ($options['include_published_at']) {
                $builder->add('publishedAt', null, [
                    'label' => 'Data',
                    'widget' => 'single_text',
                ]);
            }
        } else {
            $message = 'object not found';

            throw new NotFoundHttpException($message);
        }
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'include_published_at' => false,
        ]);
    }
}