<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Gitbox\Bundle\CoreBundle\Entity\UserAccount;
use Gitbox\Bundle\CoreBundle\Helper\ModuleHelper;
use Gitbox\Bundle\CoreBundle\Helper\PermissionHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\BrowserKit\Request;

class UserProfileController extends Controller
{

	/** Akcja dla ukazania profilu usera
	 * @Route("user/{login}", name="user_profile_index")
	 * @Template()
	 */
	public function indexAction($login) {

		$user = $this->getUserByLogin($login);
		$userDescription = $user->getIdDescription();

		/**
		 * @var $permissionHelper PermissionHelper
		 */
		$permissionHelper = $this->container->get('permissions_helper');
		$owner            = $permissionHelper->checkPermission($login);

		return array('login' => $user->getLogin(), 'email' => $user->getEmail(), 'userGroup' => $user->getIdGroup()->getDescription(),'description' => $userDescription->getContent(), 'registerDate' => $userDescription->getRegistrationDate()->format('Y-m-d H:m:s'), 'isOwner' => $owner);
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


	    return array('login' => $user->getLogin(), 'email' => $user->getEmail(), 'module' => $availableModules, 'isOwner' => $owner);
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
		if($owner) {
			return array('login' => $user->getLogin(),'email' => $user->getEmail(), 'isOwner' => $owner);
		}else {
			throw $this->createNotFoundException("Przepraszamy, ale nie ma takiej strony, bądź nie masz do niej dostępu");
		}
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

			return array('login' => $user->getLogin(),'email' => $user->getEmail(), 'isOwner' => $owner, 'form' => $form->createView());
		}else {
			throw $this->createNotFoundException("Przepraszamy, ale nie ma takiej strony, bądź nie masz do niej dostępu");
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

			return array('login' => $user->getLogin(),'email' => $user->getEmail(), 'isOwner' => $owner, 'form' => $form->createView());
		}else {
			throw $this->createNotFoundException("Przepraszamy, ale nie ma takiej strony, bądź nie masz do niej dostępu");
		}
	}

	/**
	 * @Route("/user/{login}/search", name="user_profile_search")
	 * @Template()
	 */
	public function searchAction($login) {
		return array('user' => $this->getUserByLogin($login));
	}

	/**
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
