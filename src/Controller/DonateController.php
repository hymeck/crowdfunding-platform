<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Payment;
use App\Entity\UserBonus;
use App\Repository\BonusRepository;
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
    private BonusRepository $bonusRepository;

    /**
     * DonateController constructor.
     * @param UserRepository $userRepository
     * @param Security $security
     * @param CampaignRepository $campaignRepository
     * @param BonusRepository $bonusRepository
     */
    public function __construct(
        UserRepository $userRepository,
        Security $security,
        CampaignRepository $campaignRepository,
        BonusRepository $bonusRepository)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->campaignRepository = $campaignRepository;
        $this->bonusRepository = $bonusRepository;
    }

    #[Route('/donate', 'campaign_donate')]
    public function donate() : Response
    {
        $cid = $_GET['cid'];
        $uid = $_GET['uid'];
        $money = (int) $_POST['_money'];
        $donator =$this->userRepository->find($uid);

        $campaign = $this->campaignRepository->find($cid);
        if ($campaign == null)
            return $this->createNotFoundException('no such campaign');

        if ($campaign->getUser()->getId() == $donator->getId())
            return $this->redirectToRoute('user_campaigns');

        $campaign->setCurrentMoneyAmount($campaign->getCurrentMoneyAmount() + $money);

        $bonuses = $this->bonusRepository->findBy(['campaign' => $cid]);

        $now = date_create();

        $payment = new Payment();
        $payment
            ->setMoneyAmount($money)
            ->setMadeAt($now)
            ->setCampaign($campaign);
        $donator->addPayment($payment);

        $entityManager = $this->getDoctrine()->getManager();

        foreach ($bonuses as $bonus)
        {
            if ($bonus->getPrice() != $payment->getMoneyAmount())
                continue;

            $user_bonus = new UserBonus();
            $user_bonus
                ->setUser($donator)
                ->setBonus($bonus)
                ->setPayment($payment);


            $entityManager->persist($user_bonus);
        }

        $entityManager->persist($payment);
        $entityManager->persist($campaign);
        $entityManager->flush();

        return $this->redirectToRoute('user_bonuses');
    }
}
