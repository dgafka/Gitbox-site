<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class StaticPageController extends Controller
{
    /**
     * @Route("/features", name="features_url")
     * @Template()
     */
    public function featuresAction()
    {
	    /**
	     * @var $helper \Gitbox\Bundle\CoreBundle\Helper\PermissionsHelper
	     */
	    $permissionHelper   = $this->container->get('permissions_helper');
	    $isLogged = $permissionHelper->isLogged();
	    $login    = '';

	    if($isLogged) {
		    $login = $this->container->get('session')->get('username');
	    }else {
		    return array();
	    }


		/**
		 * @var $helper \Gitbox\Bundle\CoreBundle\Helper\UserAccountHelper
		 */
		$helper = $this->container->get('user_helper');
        $user = $helper->findByLogin($login);

	    /**
	     * @var $moduleHelper ModuleHelper
	     */
	    $moduleHelper = $this->container->get('module_helper');
	    $modules = $moduleHelper->getUserModules($login);
	    $availableModules = array();

		foreach($modules as $module) {
			$availableModules[$module->getName()] = $module->getDescription();
		}

	    return array('login' => $user->getLogin(), 'email' => $user->getEmail(), 'module' => $availableModules);

    }

    /**
     * @Route("/help", name="help_url")
     * @Template()
     */
    public function helpAction()
    {
        return array();
    }

    /**
     * @Route("/about", name="about_url")
     * @Template()
     */
    public function aboutAction()
    {
        return array();
    }

}
