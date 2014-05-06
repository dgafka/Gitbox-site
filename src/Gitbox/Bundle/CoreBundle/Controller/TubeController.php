<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Gitbox\Bundle\CoreBundle\Entity\Attachment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gitbox\Bundle\CoreBundle\Entity\Content;
use Symfony\Component\HttpFoundation\Request;
use Gitbox\Bundle\CoreBundle\Form\Type\TubePostType;
use Gitbox\Bundle\CoreBundle\Entity\Menu;

class TubeController extends Controller
{

    private $dir = __DIR__;

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

//        print_r($posts[1]);
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

    /**
     * Dodawanie nowego filmu
     *
     * @Route("/user/{login}/tube/new", name="user_new_tube")
     * @Template()
     */
    public function newAction(Request $request, $login)
    {
        // walidacja dostępu
        $user = $this->validateURL($login);
        //$this->checkAccess($login);

        // utworzenie instancji wpisu
        //$postContent = new Content();
        $newAttachment = new Attachment();
        $newContent = new Content();

        // formularz nowego wpisu
        $form = $this->createForm(new TubePostType(), $newAttachment, array('csrf_protection' => true));
        $form->handleRequest($request);

        // walidacja formularza
        if ($form->isValid()) {
            $contentHelper = $this->container->get('tube_content_helper');
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('GitboxCoreBundle:Menu');

            //$newContent->setIdUser($user->getId());
            //$newContent->setCreateDate(new \DateTime('now'));
            //$newContent->setLastModificationDate(new \DateTime('now'));
            //$newContent->setIdMenu($repository->findOneByTitle('GitTube'));


            $newAttachment->setCreateDate(new \DateTime('now'));
            $newAttachment->setDescription('Description');
            $newAttachment->setTitle('Title');
            $newAttachment->setStatus('A');
            $newAttachment->setIdContent(5);


            $newAttachment->setFilename($contentHelper->getFilename($request));


            //$newContent = $contentHelper->insertIntoContent($newContent);
            //$newAttachment->setIdContent($newContent->getId());
            $contentHelper->insertIntoAttachment($newAttachment);

            // inicjalizacja flash baga
            $session = $this->container->get('session');
            $session->getFlashBag()->add('success', 'Dodano wpis <b>' . $newAttachment->getFilename() . '</b>');

            return $this->redirect(
                $this->generateUrl('tube_index', array(
                    'login' => $user->getLogin(),
                    //'id' => $newContent->getId()
                ))
            );
        }

        return array(
            'user' => $user,
            'form' => $form->createView(),
            'btnLabel' => 'Dodaj wpis'
        );
    }
}
