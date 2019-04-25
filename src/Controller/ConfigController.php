<?php

namespace App\Controller;

use App\Entity\Config;
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
            $this->addFlash('success', ' Daten wurden angelegt');
            return $this->redirectToRoute('config.index');
        }

        return $this->render('config/index.html.twig', [
            'form' => $form->createView(),
            'configs' => $this->configRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="config.edit")
     *
     * @param Request $request
     * @param Config $config
     *
     * @return Response
     */
    public function edit(Request $request, Config $config): Response
    {
        $form = $this->createForm(ConfigType::class, $config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', ' Daten wurden geändert');
            return $this->redirectToRoute('config.index');
        }

        return $this->render('config/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="config.delete")
     *
     * @param Request $request
     * @param Config $config
     *
     * @return Response
     */
    public function delete(Request $request, Config $config): Response
    {
        if ($this->isCsrfTokenValid('delete' . $config->getId(), $request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($config);
            $em->flush();
            $this->addFlash('success', 'Daten wurden gelöscht');
            return $this->redirectToRoute('config.index');
        }
        $this->addFlash('warning', 'Daten konnten nicht gelöscht werden');

        return $this->redirectToRoute('config.index');
    }
}
