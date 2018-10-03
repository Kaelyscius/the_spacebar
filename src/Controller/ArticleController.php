<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 01/10/2018
 * Time: 11:09.
 */

namespace App\Controller;

use App\Entity\Article;
use App\Service\MarkdownHelper;
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
     * @return Response
     */
    public function homepage()
    {
        return $this->render('article/homepage.html.twig');
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     *
     * @param                        $slug
     * @param MarkdownHelper         $markdownHelper
     * @param bool                   $isDebug        // viens du service.yaml
     * @param SlackClient            $slack
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function show($slug, MarkdownHelper $markdownHelper, bool $isDebug, SlackClient $slack, EntityManagerInterface $em)
    {
        if ('khaaan' == $slug) {
            $slack->sendMessage('Kahn', 'Ah, Kirk, my old friend...');
        }

        $repository = $em->getRepository(Article::class);
        /** @var Article $article */
        $article = $repository->findOneBy(['slug' => $slug]);

        if (!$article) {
            throw $this->createNotFoundException(sprintf('No article for slug "%s"', $slug));
        }
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
     */
    public function toggleArticleHeart($slug, LoggerInterface $logger)
    {
        //TODO :actually heart/unheart the article!
        $logger->info('The article has been hearted');

        return $this->json(['hearts' => random_int(5, 100)]);
    }
}
