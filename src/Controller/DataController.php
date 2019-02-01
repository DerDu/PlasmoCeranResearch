<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Config;
use App\Entity\Process;
use App\Repository\ArticleRepository;
use App\Repository\ConfigRepository;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @var ArticleRepository
     */
    private $articleRepository;
    /**
     * @var ConfigRepository
     */
    private $configRepository;

    public function __construct(ArticleRepository $articleRepository, ConfigRepository $configRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->configRepository = $configRepository;
    }

    /**
     * @Route("/", name="data.index")
     *
     * @return Response
     */
    public function index()
    {

        $dummy = true;

        if( $dummy ) {

            $fp = fopen($this->getParameter('kernel.project_dir') . '/var/import/0072_20170504.csv', 'r');
            $csv = [];
            while (($row = fgetcsv($fp, 1000, ";")) !== false) {
                $csv[] = array_map('trim', $row);
            }
            fclose($fp);

            $em = $this->getDoctrine()->getManager();

            $a = new Article();
            $a->setName('Alu Quader');
            $em->persist($a);
            $em->flush();

            $c = new Config();
            $c->setArticle($a);
            $c->setOverlayText('TestBöttger1');
            $c->setVoltageStart('100');
            $c->setVoltageLimit('297');
            $c->setCurrentLimit('200');
            $c->setIntensityLimit('50');
            $c->setIntensityThreshold('20');
            $c->setIntensityHysteresis('10');
            $c->setVoltageStep('0.5');
            $em->persist($c);
            $em->flush();

            try {
                $uuid = Uuid::uuid4();
            } catch (\Exception $exception) {
                $uuid = uniqid(__METHOD__, true);
            }

            array_walk($csv, function ($v) use ($em, $a, $c, $uuid) {
                $e = new Process();
                $e->setArticle($a);
                $e->setConfig($c);
                $e->setProcess($uuid);
                $e->setTimestamp(new \DateTime($v[0]));
                $e->setCounterFrame((int)$v[1] ?? 0);
                $e->setThresholdPixel((int)($v[2] ?? 0));
                $e->setVoltageValue((float)($v[3] ?? 0.0));
                $e->setCurrentValue((float)($v[4] ?? 0.0));
                $e->setTemperatureValue((float)($v[5] ?? 0.0));
                $e->setRemarkValue((string)($v[6] ?? ''));
                $em->persist($e);
            });
            $em->flush();

        }

        return $this->render('data/index.html.twig', [
            'articles' => $this->articleRepository->findAll(),
            'configs' => $this->configRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}/select", name="data.select")
     *
     * @param Request $request
     * @param Config $config
     *
     * @return Response
     */
    public function select(Request $request, Config $config): Response
    {

        return $this->render('data/index.html.twig', [
            'articles' => $this->articleRepository->findAll(),
            'configs' => $this->configRepository->findAll()
        ]);
    }
}
