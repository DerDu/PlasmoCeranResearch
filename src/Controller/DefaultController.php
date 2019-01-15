<?php

namespace App\Controller;


use App\Helper\ImportHelper;
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
     * @var ImportHelper
     */
    private $importHelper;

    public function __construct(ImportHelper $importHelper)
    {
        $this->importHelper = $importHelper;
    }


    /**
     * @Route("/")
     * @return Response
     */
    public function index()
    {

//        dd(stream_get_wrappers());

//        dd(fopen('ftp://'.urlencode('media-repository').':'.urlencode('!Media#200.220').'@192.168.200.220/documents/plasmoceran/0072_20170504.csv' , 'r'));
//
        $this->importHelper->openFtp();
        $this->importHelper->authFtp();
        $list = $this->importHelper->fetchList('/documents');
        dump($list);
        $file = current($list);
        $csv = $this->importHelper->readCsv($file);
        dump($csv);
        $this->importHelper->closeFtp();

        dd();

        return $this->render('base.html.twig', [
            'title' => ':)'
        ]);
    }
}
