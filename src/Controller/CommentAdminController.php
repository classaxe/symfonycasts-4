<?php

namespace App\Controller;

use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentAdminController extends AbstractController
{
    /**
     * @Route("/admin/comment", name="comment_admin")
     */
    public function index(CommentRepository $repository): Response
    {
        $comments = $repository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('comment_admin/index.html.twig', [
            'comments' => $comments,
        ]);
    }
}
