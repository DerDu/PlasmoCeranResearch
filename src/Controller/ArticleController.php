<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleAddType;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
     * @Route("/", name="article.index", methods="GET|POST")
     */
    public function index(Request $request): Response
    {

        $form = $this->createForm( ArticleType::class );
//
//        $form = $this->createFormBuilder($article);
//        $form->add('name', TextType::class);
        $form->add('add', SubmitType::class, ['label' => 'Artikel hinzufügen']);
//        $form = $form->getForm();

        $form->handleRequest($request);
//
        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('article.index');
        }
//
//        $form->addError(new FormError('123'));
//        $form = $form->createView();

        return $this->render('article/index.html.twig', [
//            'menus' => DefaultController::getMenu(),
            'form' => $form->createView(),
            'articles' => $this->articleRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article.edit", methods="GET|POST")
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article.edit', ['id' => $article->getId()]);
        }

        return $this->render('article/edit.html.twig', [
            'menus' => DefaultController::getMenu(),
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="article.delete", methods="DELETE")
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
        }

        return $this->redirectToRoute('article.index');
    }
}
