<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Gitbox\Bundle\CoreBundle\Helper\ModuleHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserProfileController extends Controller
{

	/** Akcja dla ukazania profilu usera
	 * @Route("user/{login}", name="user_profile_index")
	 * @Template()
	 */
	public function indexAction($login) {
		$user = $this->getUserByLogin($login);
		$userDescription = $user->getIdDescription();
		return array('login' => $user->getLogin(), 'email' => $user->getEmail(), 'userGroup' => $user->getIdGroup()->getDescription(),'description' => $userDescription->getContent(), 'registerDate' => $userDescription->getRegistrationDate()->format('Y-m-d H:m:s'));
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
		foreach($modules as $module) {
			$availableModules[$module->getName()] = $module->getDescription();
		}

	    return array('login' => $user->getLogin(), 'email' => $user->getEmail(), 'module' => $availableModules);
    }

    /**
     * @Route("/user/{login}/about", name="user_profile_about")
     * @Template()
     */
    public function aboutAction($login) {
		return array('user' => $this->getUserByLogin($login));
    }

	/**
	 * @Route("/user/{login}/search", name="user_profile_search")
	 * @Template()
	 */
	public function searchAction($login) {
		return array('user' => $this->getUserByLogin($login));
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
