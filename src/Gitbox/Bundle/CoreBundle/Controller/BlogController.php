<?php

namespace Gitbox\Bundle\CoreBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Gitbox\Bundle\CoreBundle\Entity\Content;
use Gitbox\Bundle\CoreBundle\Entity\Menu;
use Gitbox\Bundle\CoreBundle\Form\Type\BlogPostType;


class BlogController extends Controller
{

    /**
     * Zwraca informację o uprawnieniach użytkownika.
     *
     * @param $login
     * @return mixed
     */
    private function getAccess($login) {
        $permissionsHelper = $this->container->get('permissions_helper');

        $hasAccess = $permissionsHelper->checkPermission($login);

        return $hasAccess;
    }

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
        $moduleHelper->init('GitBlog');

        $user = $userHelper->findByLogin($login);

        if (!$user) {
            throw $this->createNotFoundException('Nie znaleziono użytkownika o nazwie <b>' . $login . '</b>.');
        } else if (!$moduleHelper->isModuleActivated($login)) {
            throw $this->createNotFoundException('Użytkownik <b>' . $login . '</b> nie posiada aktywowanego modułu.');
        }

        return $user;
    }

    /**
     * Walidacja dostępu do aktywności użytkownika np. dodawanie/edycja wpisu.
     *
     * @param $login
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function checkAccess($login) {
        $hasAccess = $this->getAccess($login);

        if (!$hasAccess) {
            $session = $this->container->get('session');
            $userId = $session->get('userId');

            if (!isset($userId)) {
                throw $this->createNotFoundException('Zaloguj się, aby mieć dostęp do tej aktywności.');
            }

            throw $this->createNotFoundException('Nie masz dostępu do tej aktywności.');
        }

        return $hasAccess;
    }

    /**
     * Akcja dla głównej strony bloga, wyświetlenie wszystkich wpisów
     *
     * @Route("/user/{login}/blog", name="user_blog")
     * @Template()
     */
    public function indexAction($login)
    {
        // walidacja dostępu
        $user = $this->validateURL($login);

        $hasAccess = $this->getAccess($login);

        $contentHelper = $this->container->get('blog_content_helper');

        // TODO: paginacja
        // pobranie wszystkich wpisów z bazy
        $posts = $contentHelper->getContents($login);

        return array(
            'user' => $user,
            'posts' => $posts,
            'hasAccess' => $hasAccess
        );
    }

    /**
     * Dodawanie nowego wpisu
     *
     * @Route("/user/{login}/blog/new", name="user_new_post")
     * @Template()
     */
    public function newAction(Request $request, $login)
    {
        // walidacja dostępu
        $user = $this->validateURL($login);
        $this->checkAccess($login);

        // utworzenie instancji wpisu
        $postContent = new Content();

        // formularz nowego wpisu
        $form = $this->createForm(new BlogPostType(), $postContent, array('csrf_protection' => true));
        $form->handleRequest($request);

        // walidacja formularza
        if ($form->isValid()) {
            $contentHelper = $this->container->get('blog_content_helper');
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('GitboxCoreBundle:Menu');

            $postContent->setIdUser($user->getId());
            $postContent->setCreateDate(new \DateTime('now'));
            $postContent->setLastModificationDate(new \DateTime('now'));
            $postContent->setIdMenu($repository->findOneByTitle('GitBlog'));

            $contentHelper->insert($postContent);

            // inicjalizacja flash baga
            $session = $this->container->get('session');
            $session->getFlashBag()->add('success', 'Dodano wpis <b>' . $postContent->getTitle() . '</b>');

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
            'btnLabel' => 'Dodaj wpis'
        );
    }

    /**
     * Edytowanie wpisu
     *
     * @Route("/user/{login}/blog/{id}/edit", name="user_edit_post")
     * @Template()
     */
    public function editAction(Request $request, $login, $id)
    {
        // walidacja dostępu
        $user = $this->validateURL($login);
        $this->checkAccess($login);

        $contentHelper = $this->container->get('blog_content_helper');

        // pobranie wpisu z bazy
        $postContent = $contentHelper->getOneContent($id, $login);

        $form = $this->createForm(new BlogPostType(), $postContent, array('csrf_protection' => true));
        $form->handleRequest($request);

        // walidacja formularza
        if ($form->isValid()) {
            $postContent->setLastModificationDate(new \DateTime('now'));

            $contentHelper->update($postContent);

            // inicjalizacja flash baga
            $session = $this->container->get('session');
            $session->getFlashBag()->add('success', 'Pomyślnie zaktualizowano wpis');

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
            'btnLabel' => 'Edytuj wpis',
            'post' => $postContent,
            'calledBy' => $request->get('calledBy')
        );
    }

    /**
     * Wyświetlenie pojedynczego wpisu o id = {id}
     *
     * @Route("/user/{login}/blog/{id}", name="user_show_post")
     * @Template()
     */
    public function showAction($login, $id)
    {
        // walidacja dostępu
        $user = $this->validateURL($login);

        $hasAccess = $this->getAccess($login);

        $contentHelper = $this->container->get('blog_content_helper');

        $postContent = $contentHelper->getOneContent($id, $login);
        // TODO: pobieranie treści komentarzy

        if (!$postContent) {
            throw $this->createNotFoundException('Niestety nie znaleziono wpisu.');
        }

        return array(
            'user' => $user,
            'post' => $postContent,
            'hasAccess' => $hasAccess
        );
    }

    /**
     * Usunięcie wpisu o id = {id}
     *
     * @Route("/user/{login}/blog/{id}/remove", name="user_remove_post")
     * @Method({"GET"})
     */
    public function removeAction($id, $login) {
        // walidacja dostępu
        $this->checkAccess($login);

        $contentHelper = $this->container->get('blog_content_helper');

        $postTitle = $contentHelper->find(intval($id))->getTitle();

        $contentHelper->remove(intval($id));

        // inicjalizacja flash baga
        $session = $this->container->get('session');
        $session->getFlashBag()->add('success', 'Usunięto wpis <b>' . $postTitle . '</b>');

        return $this->redirect(
            $this->generateUrl('user_blog', array(
                'login' => $login
            ))
        );
    }

}
