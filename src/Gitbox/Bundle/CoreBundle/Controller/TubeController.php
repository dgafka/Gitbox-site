<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gitbox\Bundle\CoreBundle\Form\Type\TubeAttachmentType;
use Gitbox\Bundle\CoreBundle\Form\Type\TubeContentType;

use Gitbox\Bundle\CoreBundle\Form\Type\TubePostTypeEdit;
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
        $moduleHelper->init('GitTube');

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

	    $contents = $contentHelper->getContents($login);

        $attachmentImages = $contentHelper->getAttachments($login);

        $countPost = count($contents);

        return array(
            'user' => $user,
            'posts' => $contents,
            'attachments' => $attachmentImages,
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
        $newContent = new Content();

        // formularz nowego wpisu
        $form = $this->createForm(new TubeAttachmentType(), $newAttachment);

        $form->handleRequest($request);

        // walidacja formularza
        if ($form->isValid()) {
            $fs = new Filesystem();
            $dir =  __DIR__.'/../../../../../web/uploads/tube/'.$user->getId().'/';
            $dirToDefaultImage = __DIR__.'/../../../../../web/uploads/tube/default_image.jpg';
            $contentHelper = $this->container->get('tube_content_helper');
            $menuHelper = $this->container->get('menu_helper');
            $em = $this->getDoctrine()->getManager();
            // inicjalizacja flash baga

            $session = $this->container->get('session');

            $file = $form['filename']->getData();
            $extension = $file->guessExtension();

            $allowed = array('mp4','webm','ogg','mpeg');
            if (!in_array($extension, $allowed)) {
                $session->getFlashBag()->add('warning', 'Nieprawidłowy format pliku video. Dostępne: mp4, webm, ogg, mpeg.');
                //throw $this->createNotFoundException("Nieprawidłowy format pliku video. Podano=".$extension);
            }else if ($file->getClientSize() > 83886080) {
                $session->getFlashBag()->add('warning', 'Rozmiar pliku musi być mniejszy niż 80Mb.');
            }else{
                $filename = uniqid();


                    $file->move($dir, $filename.'.'.$extension);
                $fs->copy($dirToDefaultImage,$dir.''.$filename.'.'.$extension.'.jpg');


                    $newAttachment->setFilename($filename.'.'.$extension);
                    $newAttachment->setMime($extension);
                    $menu = $menuHelper->findByUserAndModule($user->getId(), 'GitTube');
                    $newContent->setIdMenu($menu);
                    $newContent->setIdUser($user->getId());
                    $newContent->setTitle($newAttachment->getTitle());
                    $newContent->setDescription($newAttachment->getDescription());

                    $contentHelper->insertIntoContent($newContent);
                    $contentHelper->insertIntoAttachment($newAttachment,$newContent);
                    //$contentHelper->insertIntoAttachment($imageAttachment,$newContent);
                    // aktualizacja statystyk
                    $moduleHelper->setTotalContents($user->getId(), '+');

                    $session->getFlashBag()->add('success', 'Dodano film <b>' . $newAttachment->getTitle() . '</b>');
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
     * @Route("user/{login}/tube/{id}", name="user_tube_show")
     * @Template()
     */
    public function showAction($login, $id)
    {

        // walidacja dostępu
        $user = $this->validateUser($login);
        $this->validateUserModule($login);
        //$hasAccess = $this->checkAccess($login);

        $contentHelper = $this->container->get('tube_content_helper');

        $attachment = $contentHelper->getOneAttachment($id, $login);

        if (!$attachment) {
            throw $this->createNotFoundException('Niestety nie znaleziono filmu.');
        }

        $dir = '../../../../../web/uploads/tube/'.$user->getId().'/'.$attachment[0]->getFilename();
        $imgDir = '../../../../../web/uploads/tube/'.$user->getId().'/'.$attachment[0]->getFilename().'.jpg';

        return array(
            'user' => $user,
            'posts' => $attachment,
            'dir'  => $dir,
            'imgDir' => $imgDir,
            'idContent' => $id,
            //'hasAccess' => $hasAccess
        );
    }

    /**
     * Usuwa content o podanym id
     *
     * @Route("/user/{login}/tube/{id}/remove", name="tube_remove_file")
     * @Method({"GET"})
     */
    public function removeAction($id, $login) {
        $fs = new Filesystem();
        // walidacja dostępu
        $this->checkAccess($login);
        $user = $this->validateUser($login);

        // inicjalizacja flash baga
        $session = $this->container->get('session');

        $contentHelper = $this->container->get('tube_content_helper');
        $content = $contentHelper->getOneContent($id, $login);

        $contentTitle = $content->getTitle();

        $attachment = $contentHelper->getOneAttachment($content->getId(),$login)[0];

        $dir = '../../../../../web/uploads/tube/'.$user->getId().'/'.$attachment->getFilename();
//        $fs->remove($dir,$dir.'.jpg');//o tu jest usuwanie plików, but not work even if in array()
	    $this->removeFile($user->getId(), $attachment->getFilename());

        //usuniecie contentu
        $contentHelper->remove(intval($id));

        //usuniecie attachmentu
        $contentHelper->removeAttachment($attachment);

        $moduleHelper = $this->container->get('module_helper');
        $moduleHelper->init('GitTube');
        $userDescHelper = $this->container->get('user_description_helper');

        // aktualizacja statystyk
        $moduleHelper->setTotalContents($login, '-');
        $userDescHelper->updateUserScore($login, $content->getVoteUp(), $content->getVoteDown());

        $session->getFlashBag()->add('success', 'Usunięto wpis <b>' . $contentTitle . '</b>');



        return $this->redirect(
            $this->generateUrl('tube_index', array(
                'login' => $login
            ))
        );
    }

    /**
     * Edytowanie filmu
     * przychodzi id content
     * @Route("/user/{login}/tube/{id}/edit", name="tube_edit_file")
     * @Template()
     */
    public function editAction(Request $request, $login, $id)
    {
        // walidacja dostępu
        $user = $this->validateUser($login);
        $this->validateUserModule($login);
        $this->checkAccess($login);

        $contentHelper = $this->container->get('tube_content_helper');

        // pobranie wpisu z bazy
        $postContent = $contentHelper->getOneContent(intval($id), $login);
        $editAtt = $contentHelper->getOneAttachment($postContent->getId(), $login)[0];

        if (!$postContent) {
            throw $this->createNotFoundException('Niestety, nie znaleziono takiego wpisu.');
        }

        $form = $this->createForm(new TubeContentType(), $postContent);
        $form->handleRequest($request);

        // walidacja formularza
        if ($form->isValid()) {
            $postContent->setLastModificationDate(new \DateTime('now'));

            $contentHelper->update($postContent);

            $editAtt->setDescription($postContent->getDescription());
            $editAtt->setTitle($postContent->getTitle());

            $contentHelper->updateAttachment($editAtt);

            // inicjalizacja flash baga
            $session = $this->container->get('session');
            $session->getFlashBag()->add('success', 'Pomyślnie zaktualizowano.');

            return $this->redirect(
                $this->generateUrl('user_tube_show', array(
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
     * Generuje thumbnail dla attachmentu
     * Przychodzi id contentu
     * @Route("/user/{login}/tube/{id}/generate", name="tube_generate_att")
     * @Method({"GET"})
     */
    public function generateAction($id, $login) {
        // walidacja dostępu
        $this->checkAccess($login);
        $user = $this->validateUser($login);

        $contentHelper = $this->container->get('tube_content_helper');
        $content = $contentHelper->getOneContent($id, $login);

        $contentTitle = $content->getTitle();
        $attachment = $contentHelper->getOneAttachment($content->getId(),$login)[0];

        $session = $this->container->get('session');
        $fs = new Filesystem();
        $dir =  __DIR__.'/../../../../../web/uploads/tube/'.$user->getId().'/';

        $filename = $attachment->getFilename();
        $extension = $attachment->getMime();
        $file = $dir.''.$filename;

        $polecenie = 'ffmpeg -ss 00:00:01 -i '.$file.' -vframes 1 uploads/tube/'.$user->getId().'/'.$filename.'.jpg';
        shell_exec($polecenie);

        if(($fs->exists($dir.''.$filename.'.'.$extension.'.jpg'))){
            $session->getFlashBag()->add('warning', 'Wystąpił błąd podczas tworzenia miniaturki do filmu. Spróbuj ponownie.');
        }else{


            // inicjalizacja flash baga

            $session->getFlashBag()->add('success', 'Utworzono miniaturkę dla <b>' . $contentTitle . '</b>');
        }
        return $this->redirect(
            $this->generateUrl('tube_index', array(
                'login' => $login
            ))
        );
    }

	/**
	 * Zwraca sciezke dostepu do uploadu
	 */
	private function getUploadsDir() {
		return __DIR__ . $this->getDiviner() . '..' . $this->getDiviner() . '..' . $this->getDiviner() . '..' . $this->getDiviner() . '..' . $this->getDiviner() . '..' . $this->getDiviner() . 'web' . $this->getDiviner() . 'uploads' . $this->getDiviner();
	}

	private function removeFile($userId, $fileName) {
		$path = $this->getUploadsDir() . 'tube' . $this->getDiviner() . $userId . $this->getDiviner() . $fileName;
		return @unlink($path);
	}

	private function getDiviner() {
		return '/';
	}

}