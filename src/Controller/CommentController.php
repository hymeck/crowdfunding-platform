<?php

declare(strict_types=1);

namespace App\Controller;


use App\Entity\Comment;
use App\Repository\CampaignRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    private CampaignRepository $campaignRepository;
    private UserRepository $userRepository;

    /**
     * CommentController constructor.
     * @param CampaignRepository $campaignRepository
     * @param UserRepository $userRepository
     */
    public function __construct(CampaignRepository $campaignRepository, UserRepository $userRepository)
    {
        $this->campaignRepository = $campaignRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('comment', name: 'comment_campaign')]
    public function comment(Request $request) : Response
    {
        $id = $_GET['uid'];
        $cid = $_GET['cid'];
        $comment_body = $_POST['_comment'];

        $user =$this->userRepository->find($id);
        $campaign = $this->campaignRepository->find($cid);

        $now = date_create();
        $comment = new Comment();
        $comment
            ->setBody($comment_body)
            ->setPostedAt($now)
            ->setUser($user);

        $campaign->addComment($comment);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->persist($campaign);
        $entityManager->flush();

        return $this->redirectToRoute('campaign', ['id' => $cid]);
    }
}
