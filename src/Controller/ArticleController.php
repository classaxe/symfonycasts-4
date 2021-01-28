<?php
namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
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
    public function homepage(ArticleRepository $repository): Response
    {
        $articles = $repository->findAllPublishedOrderedByNewest();
        return $this->render(
            'article/homepage.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route(
     *     "/news/{slug}",
     *     name="article_show"
     * )
     * @param Article $article
     * @param SlackClient $slack
     * @return Response
     */
    public function show(Article $article, SlackClient $slack, CommentRepository $commentRepository): Response
    {
        // This trick works by having Symfony query the Article entities for the same field as the parameter
        if ($article->getSlug() === 'Khaaaan') {
            $slack->sendMessage('Khan', 'Ah Kirk, my old friend!');
        }

        $comments = $commentRepository->findBy(['article' => $article]);
        dump($comments); die;
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
     * @param Article $article
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function toggleArticleHeart(
        Article $article,
        LoggerInterface $logger,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $article->incrementHeartCount();
        $em->flush($article);

        $logger->info("Article {$article->getSlug()} is being hearted");
        return new JsonResponse([ 'hearts' => $article->getHeartCount()]);
    }
}