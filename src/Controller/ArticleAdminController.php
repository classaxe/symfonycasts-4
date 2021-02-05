<?php


namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN_ARTICLE")
 */
class ArticleAdminController extends AbstractController
{
    /**
     * @Route(
     *     "/admin/article/new",
     *     name="admin_article_new"
     * )
     */
    public function new()
    {
        return new Response('To Do');
    }
}