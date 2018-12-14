<?php

namespace SoftUniBlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SoftUniBlogBundle\Entity\Article;
use SoftUniBlogBundle\Entity\User;
use SoftUniBlogBundle\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends Controller
{
    /**
     * @Route("/article/create", name="article_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param $name
     */
    public function createAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            try {
                $file->move($this->getParameter('article_directory'), $fileName);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $currentUser = $this->getUser();
            $article->setAuthor($currentUser);
            $article->setImage($fileName);
            $article->setViewCount(0);

            $em = $this->getDoctrine()->getManager();
            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('blog_index');
            //return $this->redirect('/');
        }

        return $this->render('article/create.html.twig',
            ['form' => $form->createView()]);
    }

    /**
     * @param $id
     * @Route("/article/{id}", name="article_view")
     * @return Response
     */
    public function viewArticle($id)
    {
        $article = $this
            ->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);
        $article->setViewCount($article->getViewCount() + 1);

        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();

        return $this->render('article/article.html.twig',
            ['article' => $article]);
    }

    /**
     * @Route("/article/edit/{id}", name="article_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param int $id
     * @return Response
     * @internal param $name
     */
    public function editAction(Request $request, int $id)
    {
        $article = $this
            ->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);

        if (null === $article) {
            return $this->redirectToRoute('blog_index');
        }

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$currentUser->isAuthor($article) && !$currentUser->isAdmin()) {
            return $this->redirectToRoute('blog_index');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();

            try {
                $file->move($this->getParameter('article_directory'), $fileName);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $article->setAuthor($currentUser);
            $article->setImage($fileName);
            $em = $this->getDoctrine()->getManager();
            $em->merge($article);
            $em->flush();

            return $this->redirectToRoute('blog_index');
            //return $this->redirect('/');
        }

        return $this->render('article/edit.html.twig',
            ['form' => $form->createView(), 'article' => $article]);
    }

    /**
     * @Route("/article/delete/{id}", name="article_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @param Request $request
     * @param int $id
     * @return Response
     * @internal param $name
     */
    public function deleteAction(Request $request, int $id)
    {
        $article = $this
            ->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);

        if (null === $article) {
            return $this->redirectToRoute('blog_index');
        }

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        if (!$currentUser->isAuthor($article) && !$currentUser->isAdmin()) {
            return $this->redirectToRoute('blog_index');
        }

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setAuthor($currentUser);
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();

            return $this->redirectToRoute('blog_index');
            //return $this->redirect('/');
        }

        return $this->render('article/delete.html.twig',
            ['form' => $form->createView(), 'article' => $article]);
    }

    /**
     * @Route("/myArticles", name="myArticles")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function myArticles()
    {
        $articles = $this
            ->getDoctrine()
            ->getRepository(Article::class)
            ->findBy(['author' => $this->getUser()]);

        return $this->render('article/myArticles.html.twig',
            ['articles' => $articles]);
    }
}
