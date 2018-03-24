<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use AppBundle\Form\ArticleType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DashboardController extends Controller
{

    /**
    * @Route("/dashboard/my_articles", name="dashboard_index")
    *
    */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $entityManager = $this->getDoctrine()->getManager();
        $articles = $entityManager->getRepository(Article::class)->findBy(["author" => $this->getUser()]);

        return $this->render("Dashboard/index.html.twig", ["articles" => $articles]);
    }

    /**
    * @Route("dashboard/article/details/{id}", name="dashboard_article_details")
    *
    * @return Response
    */
    public function articleDetailsAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $article = $entityManager->getRepository(Article::class)->findOneBy(["id" => $id]);

        return $this->render("Dashboard/articleDetails.html.twig", ["article" => $article]);
    }

    /**
    * @Route("dashboard/article/add", name="dashboard_article_add")
    *
    * @return Response
    */
    public function AddArticleAction(Request $request)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        if ($request->isMethod("post")) {
            $article->setAuthor($this->getUser());
            $form->handleRequest($request);

            $article->setPublished(new \DateTime());

            $file = $article->getImage();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('image_directory'),
                $fileName
            );
            $article->setImage($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute("dashboard_article_details", ["id" => $article->getId()]);
        }

        return $this->render("Dashboard/articleAdd.html.twig", ["form" => $form->createView()]);
    }

    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

    /**
    * @Route("dashboard/article/edit/{id}", name="dashboard_article_edit")
    *
    *
    * Request $request
    * @param Article $article
    *
    * @return Response
    */
    public function EditArticleAction(Request $request, Article $article)
    {
        $this->denyAccessUnlessGranted("ROLE_USER");
        if ($this->getUser() != $article->getAuthor()) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(ArticleType::class, $article);

        if ($request->isMethod("post")) {
            $form->handleRequest($request);

            $file = $article->getImage();
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('image_directory'),
                $fileName
            );
            $article->setImage($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute("dashboard_article_details", ["id" => $article->getId()]);
        }

        return $this->render("Dashboard/articleEdit.html.twig", ["form" => $form->createView()]);
    }

    /**
    * @Route("dashboard/article/delete/{id}", name="dashboard_article_delete")
    *
    * @param Article $article
    *
    */
    public Function DeleteArticleAction(Article $article)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute("dashboard_index");
    }

}
