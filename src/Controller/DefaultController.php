<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package App\Controller
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     * @return Response
     */
    public function index()
    {
        return new Response(':)');
    }

    /**
     * @Route("/news/{slug}")
     */
    public function show( $slug )
    {
        return $this->render('article/show.html.twig',[
            'title' => ucwords(str_replace('-', ' ', $slug))
        ]);
//        return new Response(';) ..'.$slug);
    }
}
