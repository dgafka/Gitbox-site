<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Gitbox\Bundle\CoreBundle\Entity\UserAccount;
use Gitbox\Bundle\CoreBundle\Helper\ModuleHelper;
use Gitbox\Bundle\CoreBundle\Helper\PermissionHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class UserProfileController extends Controller
{

	/** Akcja dla ukazania profilu usera
	 * @Route("user/{login}", name="user_profile_index")
	 * @Template()
	 */
	public function indexAction($login, \Symfony\Component\HttpFoundation\Request $request) {

		$user = $this->getUserByLogin($login);
        if (!$user) {
            throw $this->createNotFoundException('Nie znaleziono użytkownika o nazwie <b>' . $login . '</b>.');
        }

		$userDescription = $user->getIdDescription();

		//Dodanie hita do wyświetlania
		if(!$request->cookies->get('profile_view' . $user->getId())) {
			$response = new Response();
			$response->headers->setCookie(new Cookie('profile_view' . $user->getId(), 'true', time() + 3600));
			$response->sendHeaders();
			$userDescription->setHit($userDescription->getHit() + 1);
			$this->getDoctrine()->getManager()->persist($userDescription);
			$this->getDoctrine()->getManager()->flush();
		}
		/**
		 * @var $permissionHelper PermissionHelper
		 */
		$permissionHelper = $this->container->get('permissions_helper');
		$owner            = $permissionHelper->checkPermission($login);
		$admin            = $permissionHelper->isAdmin();

		return array('login' => $user->getLogin(), 'email' => $user->getEmail(), 'userGroup' => $user->getIdGroup()->getDescription(),'description' => $userDescription->getContent(), 'registerDate' => $userDescription->getRegistrationDate()->format('Y-m-d H:i:s'), 'isOwner' => $owner, 'isAdmin' => $admin);
	}

    /**
     * @Route("/user/{login}/modules", name="user_profile_modules")
     * @Template()
     */
    public function modulesAction($login) {
	    $user = $this->getUserByLogin($login);
	    /**
	     * @var $moduleHelper ModuleHelper
	     */
	    $moduleHelper = $this->container->get('module_helper');
	    $modules = $moduleHelper->getUserModules($login);
	    $availableModules = array();

		foreach($modules as $module) {
			$availableModules[$module->getName()] = $module->getDescription();
		}

	    /**
	     * @var $permissionHelper PermissionHelper
	     */
	    $permissionHelper = $this->container->get('permissions_helper');
		$owner            = $permissionHelper->checkPermission($login);
	    $admin            = $permissionHelper->isAdmin();

	    return array('login' => $user->getLogin(), 'email' => $user->getEmail(), 'module' => $availableModules, 'isOwner' => $owner, 'isAdmin' => $admin);
    }

    /**
     * @Route("/user/{login}/settings", name="user_profile_settings")
     * @Template()
     */
    public function settingsAction($login) {
	    $user = $this->getUserByLogin($login);

	    /**
	     * @var $permissionHelper PermissionHelper
	     */
	    $permissionHelper = $this->container->get('permissions_helper');
	    $owner            = $permissionHelper->checkPermission($login);
	    $admin            = $permissionHelper->isAdmin();

		if($owner) {
			return array('login' => $user->getLogin(),'email' => $user->getEmail(), 'isOwner' => $owner, 'isAdmin' =>$admin);
		}else {
            throw $this->createNotFoundException('Zaloguj się, aby mieć dostęp do tej aktywności.');
		}
    }

    /**
     * @Route("/user/{login}/favourites", name="user_profile_favs")
     * @Template()
     */
    public function favouritesAction($login) {
        // pobranie użytkownika
        $user = $this->getUserByLogin($login);

        // pobranie uprawnień
        $permissionHelper = $this->container->get('permissions_helper');
        $isOwner = $permissionHelper->checkPermission($login);
        $isAdmin = $permissionHelper->isAdmin();

        // pobranie instancji helpera
        $favContentHelper = $this->container->get('fav_content_helper');
        $favContents = $favContentHelper->findByUserId($user->getId(), true);

        /* TODO na nigdy: wyrzucić bara z menu profilu do osobnej akcji i widoku,
         * tak aby nie ładować w każdej akcji tych samych zmiennych
         */
        return array(
            'login' => $login,
            'email' => $user->getEmail(),
            'isOwner' => $isOwner,
            'isAdmin' => $isAdmin,
            'modules' => $favContents
        );
    }

	/**
	 * @param $login
	 * @param $request
	 * @return array
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 * @Route("/user/{login}/settings/main", name="user_profile_settings_main")
	 * @Template()
	 */
	public function settingsMainAction(\Symfony\Component\HttpFoundation\Request $request, $login) {
		$user = $this->getUserByLogin($login);

		/**
		 * @var $permissionHelper PermissionHelper
		 */
		$permissionHelper = $this->container->get('permissions_helper');
		$owner            = $permissionHelper->checkPermission($login);
		if($owner) {

			$admin            = $permissionHelper->isAdmin();
			$userAccount     = new UserAccount();
			$form = $this->createForm(new \Gitbox\Bundle\CoreBundle\Form\Type\UserSettingsMainType(), $userAccount);
			$form->handleRequest($request);

			if($form->isValid()) {
				$userAccount->setPassword(md5($userAccount->getPassword()));

				$userHelper = $this->container->get('user_helper');
				$user->setPassword($userAccount->getPassword());
				$userHelper->update($user);

				return $this->redirect(
					$this->generateUrl('user_profile_index', array(
						'login'       => $user->getLogin(),
					))
				);
			}

			return array('login' => $user->getLogin(),'email' => $user->getEmail(), 'isOwner' => $owner, 'form' => $form->createView(), 'isAdmin' => $admin);
		}else {
            throw $this->createNotFoundException('Zaloguj się, aby mieć dostęp do tej aktywności.');
		}
	}

	/**
	 * @param $login
	 * @param $request
	 * @return array
	 * @Route("/user/{login}/settings/description", name="user_profile_settings_description")
	 * @Template()
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function settingsDescriptionAction(\Symfony\Component\HttpFoundation\Request $request, $login) {
		$user = $this->getUserByLogin($login);

		/**
		 * @var $permissionHelper PermissionHelper
		 */
		$permissionHelper = $this->container->get('permissions_helper');
		$owner            = $permissionHelper->checkPermission($login);

		if($owner) {
			$admin            = $permissionHelper->isAdmin();
			$userDescription = $user->getIdDescription();
			$form = $this->createForm(new \Gitbox\Bundle\CoreBundle\Form\Type\UserSettingsDescriptionType(), $userDescription);
			$form->handleRequest($request);

			if($form->isValid()) {
				$userHelper = $this->container->get('user_helper');
				$userHelper->update($user);

				return $this->redirect(
					$this->generateUrl('user_profile_index', array(
						'login'       => $user->getLogin(),
					))
				);
			}

			return array('login' => $user->getLogin(),'email' => $user->getEmail(), 'isOwner' => $owner, 'form' => $form->createView(), 'isAdmin' => $admin);
		}else {
            throw $this->createNotFoundException('Zaloguj się, aby mieć dostęp do tej aktywności.');
		}
	}

	/** Aktywuje modul
	 * @Route("/user/{login}/{module}/active", name="user_profile_module_active")
	 */
	public function activeModule($login, $module) {
		/**
		 * @var $permissionHelper PermissionHelper
		 */
		$permissionHelper = $this->container->get('permissions_helper');
		$owner            = $permissionHelper->checkPermission($login);

		if($owner) {
			/**
			 * @var $moduleHelper ModuleHelper
			 */
			$moduleHelper = $this->container->get('module_helper');
			$moduleHelper->setStatusModule($login, $module, 'A');
		}

		return $this->redirect($this->generateUrl('user_profile_modules', array('login' => $login)));
	}

	/** Dezaktywuje moduł
	 * @param $login
	 * @Route("/user/{login}/{module}/deactive", name="user_profile_module_deactive")
	 * @param $module
	 * @return array
	 */
	public function deactiveModule($login, $module) {
		/**
		 * @var $permissionHelper PermissionHelper
		 */
		$permissionHelper = $this->container->get('permissions_helper');
		$owner            = $permissionHelper->checkPermission($login);

		if($owner) {
			/**
			 * @var $moduleHelper ModuleHelper
			 */
			$moduleHelper = $this->container->get('module_helper');
			$moduleHelper->setStatusModule($login, $module, 'D');
		}

		return $this->redirect($this->generateUrl('user_profile_modules', array('login' => $login)));
	}

	/** Metoda odpowiedzialna za pobranie z bazy usera za pomocą loginu
	 * @param $login
	 * @return \Gitbox\Bundle\CoreBundle\Entity\UserAccount
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	private function getUserByLogin($login) {

		/**
		 * @var $helper \Gitbox\Bundle\CoreBundle\Helper\UserAccountHelper
		 */
		$helper = $this->container->get('user_helper');
		return $helper->findByLogin($login);
	}
}
