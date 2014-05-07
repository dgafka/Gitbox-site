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
        //$form = $this->createForm(new TubePostType(), $newAttachment, array('csrf_protection' => true));
        //Tu wrzucam generowanie formularza bo wywala błąd na ścieżkę do TubePostType wtf?
        $form = $this->createFormBuilder($newContent)
            ->add('title', 'text')
            ->add('description', 'text')
            ->add('header', 'file')
            ->add('save', 'submit')
            ->getForm();
        $form->handleRequest($request);


        // walidacja formularza
        if ($form->isValid()) {
            $contentHelper = $this->container->get('tube_content_helper');
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('GitboxCoreBundle:Menu');

            $newContent->setIdUser($user->getId());
            $newContent->setCreateDate(new \DateTime('now'));
            $newContent->setLastModificationDate(new \DateTime('now'));
            $newContent->setIdMenu($repository->findOneByTitle('GitTube'));
            $newContent->setStatus('A');
            $newContent->setHit(5);
            $newContent->setType('1');
//iduser, idmenu, status, title, header->file, description,
//createdate, hit, lastModDate,type, id_category,

            //$newAttachment->setFilename($contentHelper->getFilename($request));


            /*$fs = new Filesystem();
            if (!$fs->exists('/src/Gitbox/Bundle/CoreBundle/Resources/Uploads/Tube/'.%login))
            {
                try {
                    $fs->mkdir('/src/Gitbox/Bundle/CoreBundle/Resources/Uploads/Tube/'.$login);
                } catch (IOExceptionInterface $e) {
                    echo "An error occurred while creating your directory at ".$e->getPath();
                }
            }*/
            $strId = __ToString($user->$this->getId());
            $dir =  __DIR__.'/../../../../../web/uploads/tube/'.$strId.'/';
            $file = $form['header']->getData();

            $extension = $file->guessExtension();
            if (!$extension) {
                // extension cannot be guessed
                $extension = 'bin';
            }
            $filename = rand(1, 99999).'.'.$extension;
            $file->move($dir, $filename);
            $dir = $user->$this->getId()->toString().'/'.$filename;
            $newAttachment->setFilename($filename);
            //$newAttachment->setMime($extension);
            $newContent->setHeader($dir);

            $em->persist($newContent);
            $em->flush();

            $newAttachment->setCreateDate($newContent->getCreateDate());
            $newAttachment->setDescription($newContent->getDescription());
            $newAttachment->setTitle($newContent->getTitle());
            $newAttachment->setStatus($newContent->getStatus());
            //$newAttachment->setIdContent($newContent);

            //$newContent = $contentHelper->insertIntoContent($newContent);
            //$newAttachment->setIdContent($newContent->getId());
            //$contentHelper->insertIntoAttachment($newAttachment);

            // inicjalizacja flash baga
            $session = $this->container->get('session');
            $session->getFlashBag()->add('success', 'Dodano wpis <b>' . $newAttachment->getFilename() . '</b>');



            return $this->redirect(
                $this->generateUrl('tube_new_file', array(
                    //'login' => $user->getLogin(),
                    'login' => $login
                    //'id' => $newContent->getId()
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
     * @Route("user/{login}/tube/{id}", name="tube_content_show")
     * @Template()
     */
    public function showAction($login, $id)
    {
        // TODO: pobieranie treści posta i komentarzy
        $user = $this->validateURL($login);

        $contentHelper = $this->container->get('tube_content_helper');

        //$postContent = $contentHelper->getOneContent($id, $login);

        /*if (!$postContent) {
            throw $this->createNotFoundException('Niestety nie znaleziono wpisu.');
        }*/

        return array(
            'user' => $user,
            //'post' => $postContent
        );
    }
}