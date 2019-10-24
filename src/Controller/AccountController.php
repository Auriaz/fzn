<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @IsGranted("ROLE_USER")
 */
class AccountController extends BaseController
{
    /**
     * @Route("/account", name="app_account")
     */
    public function index(LoggerInterface $logger)
    {
        $logger->debug('Checking account page for'. $this->getUser()->getEmail());
        return $this->_render('account/profile.html.twig', [
            'title' => 'panel użytkownika',
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/account/change-{slug}", name="app_account_change")
     */
    public function edit($slug, UserPasswordEncoderInterface $passwordEncoder)
    {
         $change_data=null;
        if ($this->request->request->get('change_profile')) {
            $change_data = $this->request->request->get('change_profile');
        }

        switch ($slug) {
            case 'imie':
                $title = 'firstName';
                $change = $this->getUser()->getFirstName();

                if ($change_data) {
                    $this->getUser()->setFirstName($change_data);
                }

                break;

            case 'nazwisko':
                $title = 'firstName';
                $change = $this->getUser()->getLastName();

                if ($change_data) {
                    $this->getUser()->setLastName($change_data);
                }

                break;

            case 'nick':
                $title = 'firstName';
                $change = $this->getUser()->getNick();

                if ($change_data) {
                    $this->getUser()->setNick($change_data);
                }

                break;

            case 'avatar':
                $title = 'firstName';
                $change = $this->getUser()->getAvatar();

                if ($change_data) {
                    $this->getUser()->setNick($change_data);
                }

                break;

            case 'haslo':
                $title = 'hasło';
                $change = $title;

                // if ($change_data) {
                //     $this->getUser()->setPassword($passwordEncoder->encodePassword(
                //         $this->getUser(),
                //         $change_data);
                // }

                break;
            
            default:
                # code...
                break;
        }

        if ($change_data) {
    
            $this->em->flush();

            $this->addFlash('success', 'Aktualizacja przebiegła pomyślnie!');
            return $this->redirectToRoute('app_account', [
                'id' => $this->getUser()->getId(),
            ]);
        }

        return $this->_render('account/change_profile.html.twig', [
            'title' => $title,
            'user' => $this->getUser(),
            'slug'=> $slug,
            'change' => $change
        ]);
    }

    /**
     * @Route("/account/save", name="app_account_save")
     */
    public function save(Request $request)
    {
        $request->request->get('change_profile');
    }

    /**
     * @Route("/api/account", name="api_account")
     */
    public function accountApi()
    {
        $user = $this->getUser();

        return $this->json($user, 200, [], [
            'groups' => ['main'],
        ]);
    }
}
