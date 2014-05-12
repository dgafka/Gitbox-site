<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gitbox\Bundle\CoreBundle\Form\Type\TubePostType;
use Gitbox\Bundle\CoreBundle\Entity\Attachment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Gitbox\Bundle\CoreBundle\Entity\Content;
use Symfony\Component\HttpFoundation\Request;
use Gitbox\Bundle\CoreBundle\Entity\Menu;
use Symfony\Component\Filesystem\Filesystem;

class TubeController extends Controller
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
     * Walidacja poprawności loginu użytkownika.
     * Zwraca dane użytkownika z bazy, w przypadku gdy istnieje użytkownik o nazwie podanej w URL.
     * W innym przypadku zwraca błąd 404.
     *
     * @param $login
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function validateUser($login) {
        $userHelper = $this->container->get('user_helper');

        $user = $userHelper->findByLogin($login);

        if (!$user) {
            throw $this->createNotFoundException('Nie znaleziono użytkownika o nazwie <b>' . $login . '</b>.');
        }

        return $user;
    }

    /**
     * Walidacja statusu modułu użytkownika.
     * Zwraca instancję moduleHelper-a w przypadku, gdy użytkownik posiada aktywowany moduł.
     * W innym przypadku zwraca błąd 404.
     *
     * @param $login
     * @return object
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    private function validateUserModule($login) {
        $moduleHelper = $this->container->get('module_helper');
        $moduleHelper->init('GitBlog');

        if (!$moduleHelper->isModuleActivated($login)) {
            throw $this->createNotFoundException('Użytkownik <b>' . $login . '</b> nie posiada aktywowanego modułu.');
        }

        return $moduleHelper;
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
     * @Route("/user/{login}/tube", name="tube_index")
     * @Template()
     */
    public function indexAction($login)
    {

        // walidacja dostępu
        $user = $this->validateUser($login);
        $this->validateUserModule($login);
        $hasAccess = $this->getAccess($login);


        $contentHelper = $this->container->get('tube_content_helper');

        //$helper = $this->container->get('user_helper');
        //$user = $helper->findByLogin($login);

	    $posts = $contentHelper->getContents($login);

        $countPost = count($posts);

        return array(
            'user' => $user,
            'posts' => $posts,
            'countPosts' => $countPost,
            'hasAccess' => $hasAccess
        );
    }



    /**
     * Dodawanie nowego filmu
     *
     * @Route("/user/{login}/tube/new", name="tube_new_file")
     * @Template()
     */
    public function newAction(Request $request, $login)
    {
        // walidacja dostępu
        $user = $this->validateUser($login);
        $moduleHelper = $this->validateUserModule($login);
        $hasAccess = $this->checkAccess($login);

        // utworzenie instancji wpisu
        $newAttachment = new Attachment();
        $newContent = new Content();//iduser, idmenu, status, title, header->file, description,
        //createdate, hit, lastModDate,type, id_category,

        // formularz nowego wpisu
        $form = $this->createForm(new TubePostType(), $newAttachment);

        $form->handleRequest($request);

        // walidacja formularza
        if ($form->isValid()) {
            $contentHelper = $this->container->get('tube_content_helper');
            $menuHelper = $this->container->get('menu_helper');
            $em = $this->getDoctrine()->getManager();
            // inicjalizacja flash baga
            $session = $this->container->get('session');
            $dir =  __DIR__.'/../../../../../web/uploads/tube/'.$user->getId().'/';

            $file = $form['filename']->getData();
            $extension = $file->guessExtension();

            $allowed = array('mp4','wmv','asf','avi');
            if (!in_array($extension, $allowed)) {
                $session->getFlashBag()->add('warning', 'Nieprawidłowy format pliku video.');
                //throw $this->createNotFoundException("Nieprawidłowy format pliku video. Podano=".$extension);
            }else if ($file->getClientSize() > 83886080) {
                $session->getFlashBag()->add('warning', 'Rozmiar pliku musi być mniejszy niż 80Mb.');
            }else{
            $filename = uniqid();
            $file->move($dir, $filename.'.'.$extension);

            $newAttachment->setFilename($filename);
            $newAttachment->setMime($extension);
            $menu = $menuHelper->findByUserAndModule($user->getId(), 'GitTube');
            $newContent->setIdMenu($menu);
            $newContent->setIdUser($user->getId());
            $newContent->setTitle($newAttachment->getTitle());
            $newContent->setDescription($newAttachment->getDescription());

            $contentHelper->insertIntoContent($newContent);
            $contentHelper->insertIntoAttachment($newAttachment,$newContent);

            $session->getFlashBag()->add('success', 'Dodano film <b>' . $newAttachment->getTitle() . '</b>');

             $polecenie = '   ffmpeg -i /home/gitbox/www/Projects/Gitbox-site/src/Gitbox/Bundle/CoreBundle/Controller/536fd7e30f58d.mp4 -r 1 -f image2 php-%3d.jpeg';
             shell_exec($polecenie);
            }

            return $this->redirect(
                $this->generateUrl('tube_index', array(
                    'login' => $login,
                    'hasAccess' => $hasAccess
                ))
            );
        }

        return array(
            'user' => $login,
            'form' => $form->createView(),
            'btnLabel' => 'Dodaj wpis'
        );
    }

    /**
     * to id które przychodzi to content
     * @Route("user/{login}/tube/{id}", name="tube_content_show")
     * @Template()
     */
    public function showAction($login, $id)
    {

        // walidacja dostępu
        $user = $this->validateUser($login);
        $this->validateUserModule($login);
        $hasAccess = $this->checkAccess($login);

        $contentHelper = $this->container->get('tube_content_helper');

        $attachment = $contentHelper->getOneAttachment($id, $login);

        if (!$attachment) {
            throw $this->createNotFoundException('Niestety nie znaleziono filmu.');
        }

        $dir = '../../../../../web/uploads/tube/'.$user->getId().'/'.$attachment->getFilename().''.$attachment->getMime();
        return array(
            'user' => $user,
            'post' => $attachment,
            'dir'  => $dir,
            'idContent' => $id,
            'hasAccess' => $hasAccess
        );
    }

    /**
     * @Route("/user/{login}/tube/{id}/remove", name="tube_remove_file")
     * @Method({"GET"})
     */
    public function removeAction($id, $login) {
        // walidacja dostępu
        $this->checkAccess($login);

        $tubeContentHelper = $this->container->get('tube_content_helper');
        $contentHelper = $this->container->get('content_helper');
        $content = $contentHelper->find(intval($id));

        $contentTitle = $content->getTitle();

        $attachment = $tubeContentHelper->getOneAttachment($content->getId(),$login);
        //usuniecie contentu
        $contentHelper->remove(intval($id));

        //usuniecie attachmentu
        $tubeContentHelper->removeAttachment($attachment);

        $moduleHelper = $this->container->get('module_helper');
        $moduleHelper->init('GitTube');
        $userDescHelper = $this->container->get('user_description_helper');

        // aktualizacja statystyk
        $moduleHelper->setTotalContents($login, '-');
        $userDescHelper->updateUserScore($login, $content->getVoteUp(), $content->getVoteDown());

        // inicjalizacja flash baga
        $session = $this->container->get('session');
        $session->getFlashBag()->add('success', 'Usunięto wpis <b>' . $contentTitle . '</b>');

        return $this->redirect(
            $this->generateUrl('tube_index', array(
                'login' => $login
            ))
        );
    }
}