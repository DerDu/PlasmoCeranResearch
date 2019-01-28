<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Process;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        $fp = fopen($this->getParameter('kernel.project_dir') . '/var/import/0072_20170504.csv', 'r');
        $csv = [];
        while (($row = fgetcsv($fp, 1000, ";")) !== false) {
            $csv[] = array_map('trim', $row);
        }
        fclose($fp);

//        $em = $this->getDoctrine()->getManager();
//
//        $a = new Article();
//        $a->setName('ImportTest ' . date('Ymd-His'));
//        $em->persist($a);
//        $em->flush();
//
//        array_walk($csv, function ($v) use ($em, $a) {
//            $e = new Process();
//            $e->setArticle($a);
//            $e->setTimestamp(new \DateTime($v[0]));
//            $e->setCounterFrame((int)$v[1] ?? 0);
//            $e->setThresholdPixel((int)($v[2] ?? 0));
//            $e->setVoltageValue((float)($v[3] ?? 0.0));
//            $e->setCurrentValue((float)($v[4] ?? 0.0));
//            $e->setTemperatureValue((float)($v[5] ?? 0.0));
//            $e->setRemarkValue((string)($v[6] ?? ''));
//            $em->persist($e);
//        });
//        $em->flush();

        return $this->render('data/index.html.twig');
    }
}
