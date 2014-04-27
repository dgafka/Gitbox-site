<?php

namespace Gitbox\Bundle\CoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
        $contentHelper->init('blog');

        // TODO: paginacja
        $posts = $contentHelper->getContents($login);

        return array('user' => $user, 'posts' => $posts);
    }

    /**
     * @Route("/user/{login}/blog/new", name="user_new_post")
     * @Template()
     */
    public function newAction($login)
    {
        $this->validateURL($login);
        // TODO: walidacja - permissions

        return array();
    }

    /**
     * @Route("/user/{login}/blog/{id}/edit")
     * @Template()
     */
    public function editAction($login, $id)
    {
        $this->validateURL($login);
        // TODO: walidacja - permissions

        return array();
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
        $contentHelper->init('blog');

        $post = $contentHelper->getOneContent($id, $login);

        if (!$post) {
            throw $this->createNotFoundException('Niestety nie znaleziono wpisu.');
        }

        return array('user' => $user, 'post' => $post);
    }

}
