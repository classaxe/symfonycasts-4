<?php


namespace App\Controller;


use App\Entity\Article;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    /**
     * @Route(
     *     "/admin/article/new",
     *     name="admin_article_new"
     * )
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     */
    public function new()
    {
        return new Response('To Do');
    }

    /**
     * @Route(
     *     "/admin/article/{id}/edit",
     *     name="admin_article_news"
     * )
     */
    public function edit(Article $article)
    {
        if ($article->getAuthor() !== $this->getUser() &&
            !$this->isGranted('ROLE_ADMIN_ARTICLE')
        ) {
            throw $this->createAccessDeniedException('No Access!');
        }

        dd($article);
    }
}