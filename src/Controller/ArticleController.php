<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage()
    {
        return $this->render('article/homepage.html.twig');
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     */
    public function show($slug)
    {
        $comments = [
            'Commemnt 1 and so on',
            'Comment B the second of it',
            'The  third comment goes here'
        ];
        $title = ucwords(str_replace('-', ' ', $slug));
        return $this->render('article/show.html.twig', [
            'comments' =>   $comments,
            'title' =>      $title
        ]);
    }
}