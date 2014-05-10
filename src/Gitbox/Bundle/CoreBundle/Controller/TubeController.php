<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gitbox\Bundle\CoreBundle\Form\Type\TubePostType;
use Gitbox\Bundle\CoreBundle\Entity\Attachment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gitbox\Bundle\CoreBundle\Entity\Content;
use Symfony\Component\HttpFoundation\Request;
use Gitbox\Bundle\CoreBundle\Entity\Menu;
use Symfony\Component\Filesystem\Filesystem;

class TubeController extends Controller
{

   // private $dir = __DIR__;

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

        $countPost = count($posts);

        return array(
            'user' => $user,
            'posts' => $posts,
            'countPosts' => $countPost
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
        $user = $this->validateURL($login);
        //$this->checkAccess($login);

        // utworzenie instancji wpisu
        $newAttachment = new Attachment();
        $newContent = new Content();//iduser, idmenu, status, title, header->file, description,
        //createdate, hit, lastModDate,type, id_category,

        // formularz nowego wpisu
        $form = $this->createForm(new TubePostType(), $newAttachment);

        $form->handleRequest($request);
        $success = false;


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

            $allowed = array('mp4','wmv','asf');
            if (!in_array($extension, $allowed)) {
                $session->getFlashBag()->add('warning', 'Nieprawidłowy format pliku video.');
                //throw $this->createNotFoundException("Nieprawidłowy format pliku video. Podano=".$extension);
            }else if ($file->getClientSize() > 83886080) {
                $session->getFlashBag()->add('warning', 'Rozmiar pliku musi być mniejszy niż 80Mb.');
            }else{
            $filename = uniqid();
            $file->move($dir, $filename);

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
            $success = true;
            }
            return $this->redirect(
                $this->generateUrl('tube_index', array(
                    'login' => $login,
                    //'id' => $newContent->getId(),
                    'success' => $success
                ))
            );
        }

        return array(
            'user' => $login,
            'form' => $form->createView(),
            'success' => $success,
            'btnLabel' => 'Dodaj wpis'
        );
    }

    /**
     * @Route("user/{login}/tube/{id}", name="tube_content_show")
     * @Template()
     */
    public function showAction($login, $id)
    {

        $user = $this->validateURL($login);

        $contentHelper = $this->container->get('tube_content_helper');

        $attachment = $contentHelper->getOneContent($id, $login);

        if (!$attachment) {
            throw $this->createNotFoundException('Niestety nie znaleziono filmu.');
        }

        $dir = '../../../../../web/uploads/tube/'.$user->getId().'/'.$attachment->getFilename();

        return array(
            'user' => $user,
            'post' => $attachment,
            'dir'  => $dir
        );
    }
}