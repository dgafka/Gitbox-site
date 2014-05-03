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

    /**
     * @Route("/user/{login}/drive")
     * @Template()
     */
    public function DriveAction($login)
    {

        // walidacja dostępu
        $user = $this->validateURL($login);
    $form = $this->createForm(new DriveElementType());
     return array(
        'form' => $form->createView()
    );
    }

    /**
     * @Route("/user/{login}/drive/new")
     * @Template()
     */
    public function NewDriveItemAction($login)
    {	
	
	$form = $this->createForm(new DriveElementType());
	 return array(
		'form' => $form->createView()
	);
    }

}
