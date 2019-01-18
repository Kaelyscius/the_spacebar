<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleFormType;
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
        $form = $this->createForm(ArticleFormType::class);

        return $this->render('article_admin/new.html.twig', [
            'articleForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("admin/article/{id}/edit")
     *
     * @IsGranted("MANAGE", subject="article")
     *
     * @param Article $article
     */
    public function edit(Article $article)
    {
        dd($article);
    }
}
