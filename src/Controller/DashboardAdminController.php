<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\User;
use App\Form\UserRegistrationFormType;
use App\Message\SaveRegisteredUser;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @IsGranted("ROLE_ADMIN_DASHBOARD")
 */
class DashboardAdminController extends BaseController
{

    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function index(UserRepository $userRepository, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator, MessageBusInterface $messageBus)
    {
        $users = $userRepository->findAll();
        // $articleContent = $markdownHelper->parse($articleContent);
        $form = $this->createForm(UserRegistrationFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
            };

            $message = new SaveRegisteredUser($user);
            $messageBus->dispatch($message);

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );
        }

        return $this->render('dashboard_admin/index.html.twig', [
            'users' => $users,
            'title' => 'Użytkownicy',
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/{id}", name="admin_dashboard_user_change_role")
     */
    public function changeRole($id, UserRepository $userRepository)
    {
        $user = $userRepository->find($id);
        $newRole = $this->request->request->get('role');
        $user->setRoles([$newRole]);

        $this->em->flush();

        $role = '';
        switch ($newRole) {
            case 'ROLE_ADMIN':
                $roles = 'Admin';
                break;
            case 'ROLE_EDITOR':
                $roles = 'Redaktor';
                break;
            default:
                $roles = 'Użytkownik';
                break;
        }

        $this->addFlash('success', 'Rola użytkownika została zmieniona na "'.$roles.'" !');
        return $this->redirectToRoute('admin_dashboard', [
            'id' => $user->getId(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/{id}/activity", name="admin_dashboard_user_activity" )
     */
    public function activity($id, UserRepository $userRypo)
    {
        $user = $userRypo->find($id);
        if($user->getIsActive()) {
            $user->setIsActive(false);
            $this->addFlash('success', 'Status użytkownika zmienił się na nieaktywny!');
        } else {
            $user->setIsActive(true);
            $this->addFlash('success', 'Status użytkownika zmienił się na aktywny!');
        }

        $this->em->persist($user);
        $this->em->flush();

        return $this->redirectToRoute('admin_dashboard', [
            'id' => $user->getId(),
        ]);
    }

    /**
     * @Route("/admin/dashboard/{id}/delete", name="admin_dashboard_user_delete", methods="POST")
     */
    public function delete($id, UserRepository $userRypo)
    {
        $user = $userRypo->find($id);

        if ($user->getRoles() != ['ROLE_ADMIN']) {
            $user->setIsDelete(true);

            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash('success', 'Usunięto urzytkownika!');
        } else {
            $this->addFlash('warning', 'Administratora nie można usunąć!');
        }

        return $this->redirectToRoute('admin_dashboard');
    }

    /**
     * @Route("/admin/dashboard/{id}/edit", name="admin_dashboard_user_edit" )
     */
    public function edit($id, UserRepository $userRypo, SerializerInterface $serializer)
    {
        $user = $userRypo->find($id);

        $user =  $serializer->serialize($user, 'json', ['groups' => 'main']);
        return $this->_json($user, 201);
    }
}
