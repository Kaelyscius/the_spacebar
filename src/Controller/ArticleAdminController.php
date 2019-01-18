<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleAdminController.
 */
class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article/new")
     *
     * @IsGranted("ROLE_ADMIN_ARTICLE")
     *
     * @param EntityManagerInterface $em
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function new(EntityManagerInterface $em): Response
    {
        die('todo in progress');

        return new Response(sprintf(
            'Hiya! New Article id: #%d slug: %s',
            $article->getId(),
            $article->getSlug()
        ));
    }

    /**
     * @Route("admin/article/{id}/edit")
     *
     * @param Article $article
     */
    public function edit(Article $article)
    {
        if ($article->getAuthor() != $this->getUser() && !$this->isGranted('ROLE_ADMIN_ARTICLE')) {
            throw $this->createAccessDeniedException('No access!');
        }
        dd($article);
    }
}
