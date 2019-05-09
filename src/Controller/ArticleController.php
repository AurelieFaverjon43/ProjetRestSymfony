<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;


/**
 * Movie controller.
 * @Rest\Route("/api/articles", name="api_articles")
 */
class ArticleController extends AbstractFOSRestController
{
    /**
     * Liste tous les articles
     * @Rest\Get("/")
     *
     * @return object[]
     */
    public function getAll() {
        $repository = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repository->findAll();
        return $articles;
    }

    /**
     * Retourne un article
     * @Rest\Get("/{idArticle}")
     */
    public function getArticle(int $idArticle) {
        $article = $repository = $this->getDoctrine()->getRepository(Article::class)->find($idArticle);
        if(is_null($article))
        {
            throw new HttpException(404, "Article #".$idArticle.' n\'existe pas');
        }
        return $article;
    }


    /**
     * Ajoute un article
     * @Rest\Post("/")
     *
     */
    public function postArticle(Request $request) {
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(ArticleType::class, new Article());
        $form->submit($data);
        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $article = $form->getData();
            $em->persist($article);
            $em->flush();
            return $article;
        } else{
            return $form->getErrors();
        }
    }

    /**
     * Met à jour un article
     * @Rest\Put("/{idArticle}")
     *
     */
    public function putArticle(Request $request, int $idArticle) {
        $article = $repository = $this->getDoctrine()->getRepository(Article::class)->find($idArticle);
        if(is_null($article)){
            throw new HttpException(404, "Article #".$idArticle.' n\'existe pas');
        }
        $data = json_decode($request->getContent(), true);
        $form = $this->createForm(ArticleType::class, $article);
        $form->submit($data);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $article = $form->getData();
            $em->persist($article);
            $em->flush();
            return $article;
        } else{
            return $form->getErrors();
        }
    }
    /**
     * Supprime un article
     * @Rest\Delete("/{idArticle}")
     *
     */ public function deleteArticle(int $idArticle)
{
    $article = $repository = $this->getDoctrine()->getRepository(Article::class)->find($idArticle);
    if (is_null($article)) {
        throw new HttpException(404, "Article #" . $idArticle . ' n\'existe pas');
    } else {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();
        return JsonResponse::create(['success' => true], 200);
    }
}
}
