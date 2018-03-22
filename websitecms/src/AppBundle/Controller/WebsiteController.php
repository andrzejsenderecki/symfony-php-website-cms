<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Form\ArticleType;

class WebsiteController extends Controller
{

    /**
    * @Route("/", name="website_index")
    *
    * @return Response
    */
    public function indexAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $articles = $entityManager->getRepository(Article::class)->findAll();

        return $this->render("/Website/index.html.twig", ["articles" => $articles]);
    }

    /**
    * @Route("/article/details/{id}", name="website_article_details")
    *
    * @return Response
    */
    public function articleDetailsAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->findOneBy(["id" => $id]);

        return $this->render("Website/articleDetails.html.twig", ["article" => $article]);
    }

    /**
    * @Route("/article/add", name="website_article_add")
    *
    * @return Response
    */
    public function AddArticleAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        if ($request->isMethod("post")) {
            $form->handleRequest($request);

            $article->setPublished(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute("website_article_details", ["id" => $article->getId()]);
        }

        return $this->render("Website/articleAdd.html.twig", ["form" => $form->createView()]);
    }

    /**
    * @Route("/article/edit/{id}", name="website_article_edit")
    *
    *
    * Request $request
    * @param Article $article
    *
    * @return Response
    */
    public function EditArticleAction(Request $request, Article $article)
    {

        $form = $this->createForm(ArticleType::class, $article);

        if ($request->isMethod("post")) {
            $form->handleRequest($request);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute("website_article_details", ["id" => $article->getId()]);
        }

        return $this->render("Website/articleEdit.html.twig", ["form" => $form->createView()]);
    }

    /**
    * @Route("/article/delete/{id}", name="website_article_delete")
    *
    * @param Article $article
    *
    */
    public Function DeleteArticleAction(Article $article)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute("website_index");
    }

}
