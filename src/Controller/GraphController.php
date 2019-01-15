<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GraphController
 * @package App\Controller
 *
 * @Route("/graph")
 */
class GraphController extends AbstractController
{
    /**
     * @Route("/", name="graph.index")
     */
    public function index()
    {
        return $this->render('graph/index.html.twig');
    }
}
