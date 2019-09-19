<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Security\LoginFormAuthenticator;
use App\Form\UserRegistrationFormType;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\SaveRegisteredUser;

class SecurityController extends BaseController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'title' => 'Panel logowania',
        ]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('Problem z wylogowaniem');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator, MessageBusInterface $messageBus)
    {
        $form = $this->createForm(UserRegistrationFormType::class);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $userModel = $form->getData();

            $user = new User();

            $user->setEmail($userModel->email);
            $user->setFirstName($userModel->firstName);
            $user->setlastName($userModel->lastName);
            $user->setPassword($passwordEncoder->encodePassword(
                $user,
                $userModel->plainPassword
            ));

            // be absolutely sure they agree
            if (true === $userModel->agreeTerms) {
                $user->agreeToTerms();
            }

            $message = new SaveRegisteredUser($user);
            $messageBus->dispatch($message);


            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
            'title' => 'Twożenie konta',
            'modal' => [
                'id' => 'user_registration_modal_agreeTerms',
                'title' => 'Regulamin strony',
                'button' => 'user_registration_modal_agreeTerms_submit',
                'buttonName' => 'Akceptuje regulamin',
                'content' => 'Niniejszy regulamin określa zasady korzystania z serwisu FZN.pl, którego celem jest prezentacja ogłoszeń o zwierzętach przeznaczonych do adopcji oraz informacja o prawach zwierząt i obowiązków wobec nich.
1.	Użytkownikami FZN.pl mogą być osoby fizyczne i prawne.
2.	Korzystanie z serwisu jest dobrowolne i bezpłatne dla wszystkich użytkowników.
3.	Każdy użytkownik korzysta z serwisu FZN.pl na własną odpowiedzialność.
4.	Niedozwolone jest wykorzystywanie serwisu do innych celów sprzecznych z jego charakterem.
5.	Przez korzystanie z serwisu użytkownik zobowiązuje się do:
o	przestrzegania niniejszego regulaminu,
o	przestrzegania zasad prawa polskiego,
o	poszanowania praw innych użytkowników Internetu i serwisu FZN.pl.
6.	Administrator nie ponosi odpowiedzialności za naruszenie przez użytkowników praw autorskich oraz innych praw osób trzecich.
7.	Administrator serwisu może odmówić publikacji treści, bądź usunąć z serwera bez podania przyczyn treści, które:
o	są sprzeczne z obowiązującymi normami obyczajowymi,
o	naruszają prawa osób trzecich,
o	powstały z naruszeniem prawa,
o	są niezgodne z profilem serwisu.
8.	Administrator nie jest zobowiązany do poinformowania użytkownika o odmowie publikacji ogłoszenia, zawieszenia bądź usunięcia z serwera konta Użytkownika.
9.	Użytkownik potwierdza zapoznanie się z prawami autorskimi dotyczącymi zdjęć umieszczonych na stronie oraz zobowiązuje się do ewentualnego wykorzystywania ich jedynie w sytuacjach określonych na stronie FZN.pl i zgodnych z celem tej strony.
Przez korzystanie z serwisu FZN.pl Użytkownik akceptuje warunki określone regulaminem.
W przypadku dodatkowych pytań prosimy o kontakt: zwierzeta.niczyje@gmail.com
'
            ]
        ]);
    }

    public function acceptRegriter(Type $var = null)
    {
        # code...
    }
}
