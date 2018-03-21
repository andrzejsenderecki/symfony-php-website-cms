<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

}
