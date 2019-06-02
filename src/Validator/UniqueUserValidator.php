<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use App\Repository\UserRepository;

class UniqueUserValidator extends ConstraintValidator
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        $exitingUser = $this->userRepository->findOneBy([
            'email' => $value
            ]);
            
            if (!$exitingUser) {
                return;
            }
            
            if (null === $value || '' === $value) {
                return;
            }
                       
        // TODO: implement the validation here

        /* @var $constraint \App\Validator\UniqueUser */

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
