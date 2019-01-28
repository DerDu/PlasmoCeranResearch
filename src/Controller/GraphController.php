<?php

namespace App\Controller;

use App\Entity\Process;
use App\Helper\GraphHelper;
use App\Repository\ArticleRepository;
use App\Repository\ProcessRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
     * @var ArticleRepository $articleRepository
     */
    private $articleRepository;
    /**
     * @var ProcessRepository $processRepository
     */
    private $processRepository;

    /**
     * GraphController constructor.
     * @param ArticleRepository $articleRepository
     * @param ProcessRepository $processRepository
     */
    public function __construct(ArticleRepository $articleRepository, ProcessRepository $processRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->processRepository = $processRepository;
    }


    /**
     * @Route("/", name="graph.index", methods="GET")
     * @return Response
     */
    public function index()
    {

//        $data = $this->processRepository->findAll();
        $data = $this->processRepository->findBy([
            Process::PROPERTY_ARTICLE => 1
        ]);

        if ($data) {

            $s1 = array_map(function (Process $p) {
                return $p->getCounterFrame();
            }, $data);
            $s2 = array_map(function (Process $p) {
                return $p->getThresholdPixel();
            }, $data);
            $s3 = array_map(function (Process $p) {
                return $p->getCurrentValue();
            }, $data);
            $s4 = array_map(function (Process $p) {
                return $p->getVoltageValue();
            }, $data);

//        dd( $data[0]->getTimestamp()->getTimestamp().'000', 1484870400000 );

            $zc = new GraphHelper(
                $data[0]->getArticle()->getName(),
                'myChart',
                $data[0]->getTimestamp()->getTimestamp() . '000'
            );

            $zc->setSeries('Volt', $s4);
            $zc->setSeries('Current', $s3);
            $zc->setSeries('Frame', $s1);
            $zc->setSeries('Pixel', $s2);
        } else {
            $zc = new GraphHelper('Not found');
        }

        return $this->render('graph/index.html.twig', [
            'chart' => $zc->getChart()
        ]);
    }

    /**
     * @Route("/{id}", name="graph.chart", methods="GET")
     * @param int $id
     * @return Response
     */
    public function chart(int $id = 0)
    {

        $article = $this->articleRepository->find($id);
        $processList = $article->getProcessList();

        if (!$processList->isEmpty()) {

            $data = $processList->getValues();

            $s1 = array_map(function (Process $p) {
                return $p->getCounterFrame();
            }, $data);
            $s2 = array_map(function (Process $p) {
                return $p->getThresholdPixel();
            }, $data);
            $s3 = array_map(function (Process $p) {
                return $p->getCurrentValue();
            }, $data);
            $s4 = array_map(function (Process $p) {
                return $p->getVoltageValue();
            }, $data);

            $zc = new GraphHelper(
                $article->getName(),
                'myChart',
                $article->getTimestamp()->getTimestamp() . '000'
            );

            $zc->setSeries('Volt', $s4);
            $zc->setSeries('Current', $s3);
            $zc->setSeries('Frame', $s1);
            $zc->setSeries('Pixel', $s2);
        } else {
            $zc = new GraphHelper('Not found');
        }

        return $this->render('graph/index.html.twig', [
            'chart' => $zc->getChart()
        ]);
    }
}
