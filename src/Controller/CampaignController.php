<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\BonusRepository;
use App\Repository\CampaignRepository;
use App\Repository\CommentRepository;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CampaignController extends AbstractController
{
    private CampaignRepository $campaignRepository;
    private BonusRepository $bonusRepository;
    private NewsRepository $newsRepository;
    private CommentRepository $commentRepository;

    /**
     * CampaignController constructor.
     * @param CampaignRepository $campaignRepository
     * @param BonusRepository $bonusRepository
     * @param NewsRepository $newsRepository
     * @param CommentRepository $commentRepository
     */
    public function __construct(
        CampaignRepository $campaignRepository,
        BonusRepository $bonusRepository,
        NewsRepository $newsRepository,
        CommentRepository $commentRepository)
    {
        $this->campaignRepository = $campaignRepository;
        $this->bonusRepository = $bonusRepository;
        $this->newsRepository = $newsRepository;
        $this->commentRepository = $commentRepository;
    }


    #[Route('/campaign/{id}', name: 'campaign')]
    public function campaign(int $id): Response
    {
        $campaign = $this->campaignRepository->find($id);
        if ($campaign == null)
            return $this->createNotFoundException('There is no campaign with specified id');

        $bonuses = $this->bonusRepository->findBy(['campaign' => $id]);
        $news = $this->newsRepository->findBy(['campaign' => $id]);
        $comments = $this->commentRepository->findBy(['campaign' => $id]);

        return $this->render('campaign/campaign.html.twig', [
            'campaign' => $campaign,
            'bonuses' => $bonuses,
            'news' => $news,
            'comments' => $comments
        ]);
    }
}
