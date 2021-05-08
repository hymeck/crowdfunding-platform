<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserDataFormType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


/**
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
    private $security;
    private UserRepository $userRepository;

    public function __construct(Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    #[Route('/profile', name: 'user_profile')]
    public function profile(Request $request): Response
    {
        $user =$this
            ->userRepository->findOneBy(['username' => $this->security->getUser()->getUsername()]);
        $form = $this->createForm(UserDataFormType::class,
            [
                'username' => $user->getUsername(),
                'registered_at' => $user->getRegisteredAt()]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_username = $form->get('username')->getData();
            if ($user->getUsername() == $new_username)
                return $this->redirectToRoute('user_profile');

            $user->setUsername($new_username);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('user/profile.html.twig', [
            'userForm' => $form->createView()
        ]);
    }
}
