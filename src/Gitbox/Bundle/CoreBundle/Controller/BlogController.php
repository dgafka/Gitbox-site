<?php

namespace Gitbox\Bundle\CoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Gitbox\Bundle\CoreBundle\Entity\Content;
use Gitbox\Bundle\CoreBundle\Entity\Menu;
use Gitbox\Bundle\CoreBundle\Form\Type\BlogPostType;


class BlogController extends Controller
{

    /**
     * Walidacja poprawności URL-a. <br />
     * Zwraca dane użytkownika z bazy, w przypadku gdy istnieje użytkownik o podanej nazwie oraz gdy posiada aktywowany moduł.
     *
     * @param $login
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function validateURL($login) {
        $userHelper = $this->container->get('user_helper');
        $moduleHelper = $this->container->get('module_helper');
        $moduleHelper->init('blog');

        $user = $userHelper->findByLogin($login);

        if (!$user) {
            throw $this->createNotFoundException('Nie znaleziono użytkownika o nazwie <b>' . $login . '</b>');
        } else if (!$moduleHelper->isModuleActivated($login)) {
            throw $this->createNotFoundException('Użytkownik <b>' . $login . '</b> nie posiada aktywowanego modułu');
        }

        return $user;
    }

    /**
     * @Route("/user/{login}/blog", name="user_blog")
     * @Template()
     */
    public function indexAction($login)
    {
        $user = $this->validateURL($login);

        $contentHelper = $this->container->get('blog_content_helper');

        // TODO: paginacja
        $posts = $contentHelper->getContents($login);

        return array(
            'user' => $user,
            'posts' => $posts
        );
    }

    /**
     * @Route("/user/{login}/blog/new", name="user_new_post")
     * @Template()
     */
    public function newAction(Request $request, $login)
    {
        $user = $this->validateURL($login);
        // TODO: walidacja - permissions

        $postContent = new Content();

        $form = $this->createForm(new BlogPostType(), $postContent, array('csrf_protection' => true));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $contentHelper = $this->container->get('blog_content_helper');
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('GitboxCoreBundle:Menu');

            $postContent->setIdUser($user->getId());
            $postContent->setCreateDate(new \DateTime('now'));
            $postContent->setLastModificationDate(new \DateTime('now'));
            $postContent->setIdMenu($repository->findOneByTitle('blog'));

            $contentHelper->insert($postContent);

            return $this->redirect(
                $this->generateUrl('user_show_post', array(
                    'login' => $user->getLogin(),
                    'id' => $postContent->getId()
                ))
            );
        }

        return array(
            'user' => $user,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/user/{login}/blog/{id}/edit")
     * @Template()
     */
    public function editAction(Request $request, $login, $id)
    {
        $user = $this->validateURL($login);
        // TODO: walidacja - permissions

        $contentHelper = $this->container->get('blog_content_helper');

        $postContent = $contentHelper->getOneContent($id, $login);

        $form = $this->createForm(new BlogPostType(), $postContent, array('csrf_protection' => true));
        $form->handleRequest($request);

        if ($form->isValid()) {
            $postContent->setLastModificationDate(new \DateTime('now'));

            $contentHelper->update($postContent);

            return $this->redirect(
                $this->generateUrl('user_show_post', array(
                    'login' => $user->getLogin(),
                    'id' => $postContent->getId()
                ))
            );
        }

        return array(
            'user' => $user,
            'form' => $form->createView(),
            'post' => $postContent
        );
    }

    /**
     * @Route("/user/{login}/blog/{id}", name="user_show_post")
     * @Template()
     */
    public function showAction($login, $id)
    {
        // TODO: pobieranie treści posta i komentarzy
        $user = $this->validateURL($login);

        $contentHelper = $this->container->get('blog_content_helper');

        $postContent = $contentHelper->getOneContent($id, $login);

        if (!$postContent) {
            throw $this->createNotFoundException('Niestety nie znaleziono wpisu.');
        }

        return array(
            'user' => $user,
            'post' => $postContent
        );
    }

}
