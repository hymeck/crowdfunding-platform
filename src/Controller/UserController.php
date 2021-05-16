<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Bonus;
use App\Entity\Campaign;
use App\Entity\News;
use App\Form\BonusFormType;
use App\Form\CampaignCreationFormType;
use App\Form\NewsFormType;
use App\Form\UserDataFormType;
use App\Repository\CampaignRepository;
use App\Repository\CommentRepository;
use App\Repository\NewsRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\This;
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
    private Security $security;
    private UserRepository $userRepository;
    private TagRepository $tagRepository;
    private CampaignRepository $campaignRepository;
    private NewsRepository $newsRepository;
    private CommentRepository $commentRepository;

    public function __construct(
        Security $security,
        UserRepository $userRepository,
        TagRepository $tagRepository,
        CampaignRepository $campaignRepository,
        NewsRepository $newsRepository,
        CommentRepository $commentRepository)
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->tagRepository = $tagRepository;
        $this->campaignRepository = $campaignRepository;
        $this->newsRepository = $newsRepository;
        $this->commentRepository = $commentRepository;
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

    #[Route('/my_bonuses', name: 'user_bonuses')]
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

    #[Route('/my_campaigns', name: 'user_campaigns')]
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

    #[Route('/create_campaign', name: 'user_create_campaign')]
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

    #[Route('/my_campaigns/add_bonus', name: 'user_add_bonus')]
    public function add_bonus(Request $request) : Response
    {
        $id = $_GET['id'];

        $bonus = new Bonus();
        $form = $this->createForm(BonusFormType::class, $bonus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $now = date_create();
            $bonus->setAddedAt($now);

            $campaign = $this->campaignRepository->find($id);
            $campaign->addBonus($bonus);
            $campaign->setUpdatedAt($now);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bonus);
            $entityManager->persist($campaign);
            $entityManager->flush();

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('bonus/add_bonus.html.twig', [
                'bonusForm' => $form->createView()
            ]
        );
    }

    #[Route('/my_campaigns/add_news', name: 'user_add_news')]
    public function add_news(Request $request) : Response
    {
        $id = $_GET['id'];

        $news = new News();
        $form = $this->createForm(NewsFormType::class, data: $news);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $now = date_create();
            $news->setPostedAt($now);

            $campaign = $this->campaignRepository->find($id);
            $campaign->addNews($news);
            $campaign->setUpdatedAt($now);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($news);
            $entityManager->persist($campaign);
            $entityManager->flush();

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('news/add_news.html.twig', [
                'newsForm' => $form->createView()
            ]
        );
    }

    #[Route('my_campaigns/{id}/news', name: 'user_campaign_news_list')]
    public function campaign_news_list(int $id) : Response
    {
        $user =$this
            ->userRepository->findOneBy(['username' => $this->security->getUser()->getUsername()]);

        $campaign = $this->campaignRepository->findOneBy(['user' => $user->getId(), 'id' => $id]);

        if ($campaign == null)
            return $this->createNotFoundException('no campaign');

        $news_list = $this->newsRepository->findBy(['campaign' => $id]);

        return $this->render('campaign/news.html.twig',[
            'news' => $news_list
        ]);
    }

    #[Route('my_campaigns/{cid}/news/{nid}', name: 'user_campaign_news')]
    public function campaign_news(int $cid, int $nid) : Response
    {
        $user =$this
            ->userRepository->findOneBy(['username' => $this->security->getUser()->getUsername()]);

        $campaign = $this->campaignRepository->findOneBy(['user' => $user->getId(), 'id' => $cid]);

        if ($campaign == null)
            return $this->createNotFoundException('no campaign');

        $news = $this->newsRepository->findOneBy(['campaign' => $cid, 'id' => $nid]);

        if ($news == null)
            return $this->createNotFoundException('no news');

        return $this->render('news/news.html.twig',[
            'news' => $news
        ]);
    }


//    #[Route('my_campaigns/{cid}/news/{nid}')]
//    public function edit_news() : Response
//    {
//
//    }
}
