<?php
/**
 * Created by PhpStorm.
 * User: alexa
 * Date: 01/10/2018
 * Time: 11:09.
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController
{
    /**
     * @Route("/")
     *
     * @return Response
     */
    public function homepage()
    {
        return new Response('OMG MY FIRST PAGE');
    }

    /**
     * @Route("/news/{slug}")
     */
    public function show($slug)
    {
        return new Response(
            sprintf('future page to show : %s',
                $slug
            ));
    }
}
