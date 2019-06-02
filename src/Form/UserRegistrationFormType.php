<?php

namespace App\Form;

use App\Form\Model\UserRegistrationFormModel;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\IsTrue;

class UserRegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Imię',
                'attr' => [
                    'placeholder' => 'Imię',
                ],
            ])
            ->add( 'lastName', TextType::class, [
                'label' => 'Nazwisko',
                'attr' => [
                    'placeholder' => 'Nazwisko',
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => [
                    'placeholder' => 'Email',
                ],
            ])
    // don't use password, avoid EVER setting on a field that might be persisted
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Hasło',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Hasło',
                ],
            ])
            ->add('secondPassword', PasswordType::class, [
                'label' => 'Potwierdź',
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Potwierdź',
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'Zaakceptuj regulamin',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Jeżeli chcesz sie zarejstrować, to potwierdź regulamin!'
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserRegistrationFormModel::class
        ]);
    }
}
