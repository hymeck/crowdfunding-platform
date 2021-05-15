<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\BonusRepository;
use App\Repository\CampaignRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampaignController extends AbstractController
{
    private CampaignRepository $campaignRepository;
    private BonusRepository $bonusRepository;

    /**
     * CampaignController constructor.
     * @param CampaignRepository $campaignRepository
     * @param BonusRepository $bonusRepository
     */
    public function __construct(CampaignRepository $campaignRepository, BonusRepository $bonusRepository)
    {
        $this->campaignRepository = $campaignRepository;
        $this->bonusRepository = $bonusRepository;
    }


    #[Route('/campaign/{id}', name: 'campaign')]
    public function campaign(int $id): Response
    {
        $campaign = $this->campaignRepository->find($id);
        if ($campaign == null)
            return $this->createNotFoundException('There is no campaign with specified id');

        $bonuses = $this->bonusRepository->findBy(['campaign' => $id]);


        return $this->render('campaign/campaign.html.twig', [
            'campaign' => $campaign,
            'bonuses' => $bonuses
        ]);
    }
}
