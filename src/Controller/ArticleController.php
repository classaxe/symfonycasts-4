<?php
namespace App\Controller;

use App\Entity\Article;
use App\Service\MarkdownHelper;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route(
     *     "/",
     *     name="app_homepage"
     * )
     */
    public function homepage(): Response
    {
        return $this->render('article/homepage.html.twig');
    }

    /**
     * @Route(
     *     "/news/{slug}",
     *     name="article_show"
     * )
     * @param $slug
     * @param MarkdownHelper $markdownHelper
     * @param SlackClient $slack
     * @return Response
     */
    public function show(
        $slug,
        SlackClient $slack,
        EntityManagerInterface $em
    ): Response
    {
        if ($slug === 'Khaaaan') {
            $slack->sendMessage('Khan', 'Ah Kirk, my old friend!');
        }

        $repository = $em->getRepository(Article::class);

        /** @var Article $article */
        $article = $repository->findOneBy(['slug' => $slug]);
        if (!$article) {
            throw $this->createNotFoundException(sprintf('No article matched slug "%s"', $slug));
        }

        $comments = [
            'Commemnt 1 and so on',
            'Comment B the second of it',
            'The  third comment goes here'
        ];

        return $this->render('article/show.html.twig', [
            'article' =>        $article,
            'comments' =>       $comments
        ]);
    }

    /**
     * @Route(
     *     "/news/{slug}/heart",
     *     name="article_toggle_heart",
     *     methods={"POST"}
     * )
     * @param $slug
     * @param LoggerInterface $logger
     * @return JsonResponse
     */
    public function toggleArticleHeart($slug, LoggerInterface $logger): JsonResponse
    {
        // TODO - do it
        $logger->info("Article $slug is being hearted");
        return new JsonResponse([ 'hearts' => rand(5, 100) ]);
    }
}