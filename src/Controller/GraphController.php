<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Process;
use App\Helper\GraphHelper;
use App\Repository\ArticleRepository;
use App\Repository\ProcessRepository;
use Doctrine\ORM\NonUniqueResultException;
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
     * @throws NonUniqueResultException
     */
    public function index()
    {
        return $this->render('graph/index.html.twig', [
            'articles' => $this->articleRepository->findAll(),
            'processes' => $this->processRepository->getProgressList()
        ]);
    }

    /**
     * @Route("/{id}", name="graph.chart", methods="GET")
     * @param string $id
     * @return Response
     * @throws NonUniqueResultException
     */
    public function chart(string $id = '')
    {

        $processList = $this->processRepository->findBy( [Process::PROPERTY_PROCESS => $id] );

//        $article = $this->articleRepository->find($id);

//        $processList = $article->getProcessList();

        if (!empty($processList)) {

            /** @var Process[] $data */
            $data = $processList;

            $article = current($data)->getArticle();

            $scf = array_map(function (Process $p) {
                return $p->getCounterFrame();
            }, $data);
            $stp = array_map(function (Process $p) {
                return $p->getThresholdPixel();
            }, $data);
            $scv = array_map(function (Process $p) {
                return $p->getCurrentValue();
            }, $data);
            $svv = array_map(function (Process $p) {
                return $p->getVoltageValue();
            }, $data);
            $stv = array_map(function (Process $p) {
                return $p->getTemperatureValue();
            }, $data);
            $srv = array_map(function (Process $p) {
                return $p->getRemarkValue();
            }, $data);


            $minValue = $data[0]->getTimestamp()->getTimestamp();

            $zc = new GraphHelper(
                $article->getName(),
                'myChart',
                $minValue . '000'
            );

            $zc->setSeries('Spannung', $svv);
            $zc->setSeries('Stromstärke', $scv);
            $zc->setSeries('Funkenintensität', $stp);
            $zc->setSeries('Temperatur', $stv);
            $zc->setSeries('Frame', $scf);
            $zc->setSeries('Status', $srv);
        } else {
            $zc = new GraphHelper('Not found');
        }

        return $this->render('graph/index.html.twig', [
            'chart' => $zc->getChart(),
            'articles' => $this->articleRepository->findAll(),
            'processes' => $this->processRepository->getProgressList()

        ]);
    }
}
