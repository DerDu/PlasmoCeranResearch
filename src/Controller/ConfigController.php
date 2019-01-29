<?php

namespace App\Controller;

use App\Form\ConfigType;
use App\Repository\ConfigRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ConfigController
 * @package App\Controller
 *
 * @Route("/config")
 */
class ConfigController extends AbstractController
{

    /**
     * @var ConfigRepository $configRepository
     */
    private $configRepository;

    /**
     * ConfigController constructor.
     * @param ConfigRepository $configRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @Route("/", name="config.index")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {

        $form = $this->createForm(ConfigType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('config.index');
        }

        return $this->render('config/index.html.twig', [
            'form' => $form->createView(),
            'configs' => $this->configRepository->findAll()
        ]);
    }
}
