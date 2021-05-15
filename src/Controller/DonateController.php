<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @IsGranted("ROLE_USER")
 */
class DonateController extends AbstractController
{
    private UserRepository $userRepository;
    private Security $security;
    private CampaignRepository $campaignRepository;

    /**
     * DonateController constructor.
     * @param UserRepository $userRepository
     * @param Security $security
     * @param CampaignRepository $campaignRepository
     */
    public function __construct(UserRepository $userRepository, Security $security, CampaignRepository $campaignRepository)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->campaignRepository = $campaignRepository;
    }

//    #[Route('/campaign/{id}/donate', 'campaign_donate')]
//    public function donate(int $id) : Response
    #[Route('/donate', 'campaign_donate')]
    public function donate() : Response
    {
        $id = $_GET['id'];
        $uid = $_GET['uid'];
        $user =$this
            ->userRepository->findOneBy(['username' => $this->security->getUser()->getUsername()]);

        if ($user->getId() == $uid)
            return $this->redirectToRoute('user_campaigns');

        $campaign = $this->campaignRepository->find($id);
        if ($campaign == null)
            return $this->createNotFoundException('no such campaign');

        $bonuses = $campaign->getBonuses();

        // todo: provide bonus for user if amount of money is enough
        // todo: increase campaign's current money amount

        return $this->redirectToRoute('user_profile');
    }
}
