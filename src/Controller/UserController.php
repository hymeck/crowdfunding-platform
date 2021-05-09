<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Campaign;
use App\Form\CampaignCreationFormType;
use App\Form\UserDataFormType;
use App\Repository\SubjectMatterRepository;
use App\Repository\TagRepository;
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
    private TagRepository $tagRepository;

    public function __construct(
        Security $security,
        UserRepository $userRepository,
        TagRepository $tagRepository)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->tagRepository = $tagRepository;
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

    #[Route('my_bonuses', name: 'user_bonuses')]
    public function bonuses() : Response
    {
        $user =$this
            ->userRepository->findOneBy(['username' => $this->security->getUser()->getUsername()]);

        $bonuses = $user->getBonuses();

        return $this->render('user/user_bonuses.html.twig',[
            'username' => $user->getUsername(),
            'bonuses' => $bonuses
        ]);
    }

    #[Route('my_campaigns', name: 'user_campaigns')]
    public function campaigns() : Response
    {
        $user =$this
            ->userRepository->findOneBy(['username' => $this->security->getUser()->getUsername()]);

        $campaigns = $user->getCampaigns();

        return $this->render('user/user_campaigns.html.twig',[
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'campaigns' => $campaigns
        ]);
    }

    #[Route('create_campaign', name: 'user_create_campaign')]
    public function create_campaign(Request $request) : Response
    {
        $user =$this
            ->userRepository->findOneBy(['username' => $this->security->getUser()->getUsername()]);

        $campaign = new Campaign();

        $tags = $this->tagRepository->findAll();

        $form = $this->createForm(CampaignCreationFormType::class, $campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $campaign->setUpdatedAt(date_create());
            $user->addCampaign($campaign);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->persist($campaign);
            $entityManager->flush();

            return $this->redirectToRoute('user_campaigns');
        }

        return $this->render('campaign/create_campaign.html.twig', [
            'campaignForm' => $form->createView(),
            'tags' => $tags
        ]);
    }
}
