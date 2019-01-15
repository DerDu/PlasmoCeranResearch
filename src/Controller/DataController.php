<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DataController
 * @package App\Controller
 *
 * @Route("/data")
 */
class DataController extends AbstractController
{
    /**
     * @Route("/", name="data.index")
     */
    public function index()
    {
        return $this->render('data/index.html.twig');
    }

    /**
     * @Route("/import", name="data.import")
     */
    public function import()
    {
        return $this->render('data/import.html.twig');
    }
}
