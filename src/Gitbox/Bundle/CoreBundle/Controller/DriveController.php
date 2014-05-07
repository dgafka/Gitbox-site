<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Gitbox\Bundle\CoreBundle\Form\Type\DriveElementType;
use Symfony\Component\HttpFoundation\Request;
use Gitbox\Bundle\CoreBundle\Entity\Content;
use Gitbox\Bundle\CoreBundle\Entity\Menu;
use Symfony\Component\HttpFoundation\Session\Session;

class DriveController extends Controller
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
        $moduleHelper->init('GitDrive');

        $user = $userHelper->findByLogin($login);

        if (!$user) {
            throw $this->createNotFoundException('Nie znaleziono użytkownika o nazwie <b>' . $login . '</b>.');
        } else if (!$moduleHelper->isModuleActivated($login)) {
            throw $this->createNotFoundException('Użytkownik <b>' . $login . '</b> nie posiada aktywowanego modułu.');
        }

        return $user;
    }

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
     * @Route("/user/{login}/drive")
     * @Template()
     */
    public function DriveAction($login)
    {

        // walidacja dostępu
        $user = $this->validateURL($login);

     return array(
        'user' => $user
    );
    }

    /**
     * @Route("/new/drive")
     * @Template()
     */
    public function NewDriveItemAction()
    {
        // walidacja dostępu
        $user = $this->validateURL("kakaka");
        $this->checkAccess("kakaka");

        // utworzenie instancji wpisu
        $postContent = new Content();
	$form = $this->createForm(new DriveElementType());
	 return array(
         'user' => $user,
		'form' => $form->createView()
	);
    }


    /**
     * @Route("new/drive/contener")
     * @Template()
     */
    public function NewDriveContenerAction()
    {
        // walidacja dostępu
        $user = $this->validateURL("kakaka");
        $this->checkAccess("kakaka");

        // utworzenie instancji wpisu
        $postContent = new Content();
        $form = $this->createForm(new DriveElementType());
        return array(
            'user' => $user,
            'form' => $form->createView()
        );
    }



    /**
     * @Route("/user/{login}/drive/test")
     * @Template("GitboxCoreBundle:Drive:Drive.html.twig")
     */
    public function DriveFormTestAction($login)
    {

        $form = $this->createForm(new DriveElementType());
        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/user/{login}/drive/{element}")
     * @Template()
     */
    public function DriveShowAction($login, $element)
    {

        $userHelper = $this->container->get('user_helper');
        $user = $userHelper->findByLogin($login);
        $form = $this->createForm(new DriveElementType());
        return array(
            'form' => $form->createView(),
            'user' => $user
        );
    }

    /**
     * @Route("/edit/drive/{element}")
     * @Template()
     */
    public function DriveEditAction($element)
    {

        $userHelper = $this->container->get('user_helper');
        $user = $userHelper->findByLogin("kakaka");
        $form = $this->createForm(new DriveElementType());
        return array(
            'form' => $form->createView(),
            'user' => $user

        );
    }

}
