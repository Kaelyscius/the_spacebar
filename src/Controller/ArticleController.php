<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 01/10/2018
 * Time: 11:09.
 */

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     *
     * @param ArticleRepository $repository
     *
     * @return Response
     */
    public function homepage(ArticleRepository $repository)
    {
        //Pas besoin d'entity manager parceque le repository est déjà enregistré comme un service dans le container
//        $repository = $em->getRepository(Article::class);
        // Le premier paramètre est whère. Si on passe rien, tout match. (Permet de faire un find all avec critère)
//        $articles = $repository->findBy([], ['publishedAt' => 'DESC']);
        $articles = $repository->findAllPublishedOrderedByNewest();

        return $this->render('article/homepage.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     *
     * @param Article     $article
     * @param SlackClient $slack
     *
     * @return Response
     */
    public function show(Article $article, SlackClient $slack)
    {
        if ('khaaan' == $article->getSlug()) {
            $slack->sendMessage('Kahn', 'Ah, Kirk, my old friend...');
        }

        // Plus besoin quand on passe direct en parametre l'objet
        // Si il ne trouve pas d'objet qui correspond, il renvoie automatiquement une 404
//        $repository = $em->getRepository(Article::class);
//        /** @var Article $article */
//        $article = $repository->findOneBy(['slug' => $article->getSlug()]);
//
//        if (!$article) {
//            throw $this->createNotFoundException(sprintf('No article for slug "%s"', $article->getSlug()));
//        }
        $comments = [
            'I ate a normal rock once. It did NOT taste like bacon!',
            'Woohoo! I\'m going on an all-asteroid diet!',
            'I like bacon too! Buy some from my site! bakinsomebacon.com',
        ];

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => $comments,
            ]);
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     *
     * @param Article         $article
     * @param LoggerInterface $logger
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @throws \Exception
     */
    public function toggleArticleHeart(Article $article, LoggerInterface $logger, EntityManagerInterface $em)
    {
        $article->incrementHeartCount();
        $em->flush();

        $logger->info('The article has been hearted');

        return $this->json(['hearts' => $article->getHeartCount()]);
    }
}
