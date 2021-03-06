<?php

namespace App\Controller;

use App\Entity\Process;
use App\Form\ImportType;
use App\Repository\ArticleRepository;
use App\Repository\ConfigRepository;
use DateTime;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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

        return $this->render('data/index.html.twig', [
            'articles' => $this->articleRepository->findAll(),
            'configs' => $this->configRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}/select", name="data.select")
     *
     * @param Request $request
     * @param int $id
     *
     * @return Response
     */
    public function select(Request $request, int $id): Response
    {

        $form = $this->createForm(ImportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $file */
            $file = $form['attachment']->getData();

            if(
                $file->getClientMimeType() == 'application/vnd.ms-excel'
                || $file->getMimeType() == 'text/plain'
            ) {

                $fp = fopen($file->getRealPath(), 'r');
                $csv = [];
                while (($row = fgetcsv($fp, 1000, ";")) !== false) {
                    $csv[] = array_map('trim', $row);
                }
                fclose($fp);

                $em = $this->getDoctrine()->getManager();

                $c = $this->configRepository->find($id);
                $a = $c->getArticle();

                try {
                    // drop first uuid in case of restart, bug: gets last uuid instead of new one
                    Uuid::uuid4();
                    // get valid uuid
                    $uuid = Uuid::uuid4();
                } catch (Exception $exception) {
                    $uuid = uniqid(__METHOD__, true);
                }

                array_walk($csv, function ($v) use ($em, $a, $c, $uuid, &$ep) {

                    if( $ep instanceof Process && $ep->getTimestamp() == new DateTime($v[0]) ) {
                        $ep->setThresholdPixel(
                            ($ep->getThresholdPixel() +(int)($v[2] ?? 0))
                            / 2
                        );
                        $ep->setVoltageValue(
                            ($ep->getVoltageValue() +(float)($v[3] ?? 0.0))
                            / 2
                        );
                        $ep->setCurrentValue(
                            ($ep->getCurrentValue() +(float)($v[4] ?? 0.0))
                            / 2
                        );
                        $ep->setTemperatureValue(
                            ($ep->getTemperatureValue() + (float)($v[5] ?? 0.0))
                            / 2
                        );
                        if( !preg_match( '!'.preg_quote((string)($v[6] ?? '')).'!is', $ep->getRemarkValue() ) ) {
                            $ep->setRemarkValue(
                                $ep->getRemarkValue() .', '. (string)($v[6] ?? '')
                            );
                        }
                        $em->persist($ep);
                    } else {
                        $e = new Process();
                        $e->setArticle($a);
                        $e->setConfig($c);
                        $e->setProcess($uuid);
                        $e->setTimestamp(new DateTime($v[0]));
                        $e->setCounterFrame((int)$v[1] ?? 0);
                        $e->setThresholdPixel((int)($v[2] ?? 0));
                        $e->setVoltageValue((float)($v[3] ?? 0.0));
                        $e->setCurrentValue((float)($v[4] ?? 0.0));
                        $e->setTemperatureValue((float)($v[5] ?? 0.0));
                        $e->setRemarkValue((string)($v[6] ?? ''));
                        $em->persist($e);
                        $ep = $e;
                    }
                });

                $em->flush();

                $this->addFlash('success', 'Daten wurden importiert');

                return $this->redirectToRoute('graph.index');

            }
        }

        return $this->render('data/import.html.twig', [
            'articles' => $this->articleRepository->findAll(),
            'config' => $this->configRepository->find($id),
            'form' => $form->createView()
        ]);
    }


}
