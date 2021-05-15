<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CampaignRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private TagRepository $tagRepository;
    private CampaignRepository $campaignRepository;

    public function __construct(TagRepository $tagRepository, CampaignRepository $campaignRepository)
    {
        $this->tagRepository = $tagRepository;
        $this->campaignRepository = $campaignRepository;
    }

    #[Route("/", name: "homepage")]
    public function index() : Response
    {
        $tags = $this->tagRepository->findAll();
        $last_updated_campaigns = $this->campaignRepository->findBy([], ['updated_at' => 'ASC'], limit: 3);

        return $this->render('home/index.html.twig', [
           'tags' => $tags,
           'lastUpdatedCampaigns' => $last_updated_campaigns
        ]);
    }
}
