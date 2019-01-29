<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleAddType;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller
 *
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @var null|ArticleRepository $articleRepository
     */
    private $articleRepository = null;

    /**
     * ArticleController constructor.
     * @param ArticleRepository $articleRepository
     */
    public function __construct( ArticleRepository $articleRepository )
    {
        $this->articleRepository = $articleRepository;
    }


    /**
     * @Route("/", name="article.index")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {

        $form = $this->createForm( ArticleType::class );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('article.index');
        }

        return $this->render('article/index.html.twig', [
            'form' => $form->createView(),
            'articles' => $this->articleRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article.edit")
     *
     * @param Request $request
     * @param Article $article
     *
     * @return Response
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', $article->getName().' wurde geändert');
            return $this->redirectToRoute('article.index');
        }

        return $this->render('article/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="article.delete")
     *
     * @param Request $request
     * @param Article $article
     *
     * @return Response
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
            $this->addFlash('success', $article->getName().' wurde gelöscht');
            return $this->redirectToRoute('article.index');
        }
        $this->addFlash('danger', $article->getName().' wurde nicht gelöscht');

        return $this->redirectToRoute('article.index');
    }
}
