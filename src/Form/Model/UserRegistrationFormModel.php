<?php

namespace App\Form\Model;

use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;


class UserRegistrationFormModel
{
    /**
     * @Assert\NotBlank(message="Please enter an email")
     */
    public $firstName;

    /**
     * @Assert\NotBlank(message="Please enter an email")
     */
    public $lastName;

    /**
     * @Assert\NotBlank(message="Please enter an email")
     * @Assert\Email()
     * @UniqueUser()
     */
    public $email;

    /**
     * @Assert\NotBlank(message="Wybierz hasło!")
     * @Assert\Length(min=5, minMessage="Hasło powino składać się przynajmniej z 5 znaków.")
     */
    public $plainPassword;

    // /**
    //  * @Assert\NotBlank(message="Wpisz to samo hasło!")
    //  * @Assert\Length(min=5, minMessage="Hasło powino składać się przynajmniej z 5 znaków.")
    //  */
    // public $secondPassword;
    
    /**
     * @Assert\IsTrue(message="I know, it's silly, but you must agree to our terms.")
     */
    public $agreeTerms;
}
