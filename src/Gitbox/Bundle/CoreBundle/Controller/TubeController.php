<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gitbox\Bundle\CoreBundle\Entity\Content;
use Symfony\Component\HttpFoundation\Request;
use Gitbox\Bundle\CoreBundle\Form\Type\TubePostType;

class TubeController extends Controller
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
        $moduleHelper->init('GitTube');

        $user = $userHelper->findByLogin($login);

        if (!$user) {
            throw $this->createNotFoundException('Nie znaleziono użytkownika o nazwie <b>' . $login . '</b>');
        } else if (!$moduleHelper->isModuleActivated($login)) {
            throw $this->createNotFoundException('Użytkownik <b>' . $login . '</b> nie posiada aktywowanego modułu');
        }

        return $user;
    }


    /**
     * @Route("/user/{login}/tube", name="tube_index")
     * @Template()
     */
    public function indexAction($login)
    {

	    $helper = $this->container->get('user_helper');

        $contentHelper = $this->container->get('tube_content_helper');

        $user = $helper->findByLogin($login);

	    $posts = $contentHelper->getContents($login);

        return array('user' => $user, 'posts' => $posts);
    }

    /**
     * @Route("user/{login}/tube/{id}", name="tube_content_show")
     * @Template()
     */
    public function showAction($login, $id)
    {
        // TODO: pobieranie treści posta i komentarzy
        $user = $this->validateURL($login);

        $contentHelper = $this->container->get('tube_content_helper');

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
