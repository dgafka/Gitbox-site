<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Gitbox\Bundle\CoreBundle\Entity\UserAccount;
use Gitbox\Bundle\CoreBundle\Form\Type\UserHelp;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

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

	    if ($isLogged) {
		    $login = $this->container->get('session')->get('username');
	    } else {
		    return array();
	    }

        $permissionHelper = $this->container->get('permissions_helper');
        $owner = $permissionHelper->checkPermission($login);
        $admin = $permissionHelper->isAdmin();


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

	    return array('login' => $user->getLogin(), 'email' => $user->getEmail(), 'module' => $availableModules, 'isOwner' => $owner, 'isAdmin' => $admin);

    }

    /**
     * @Route("/help", name="help_url")
     * @Template()
     */
    public function helpAction(Request $request)
    {

	    $userAccount = new UserAccount();

	    $form = $this->createForm(new UserHelp());

	    $form->handleRequest($request);
	    if($form->isValid()) {
		    $email = $form->getData();
		    $email = $email['email'];
		    $content = $form->getData();
		    $content = $content['content'];

		    return $this->forward('GitboxCoreBundle:Mailer:helpMail', array('email' => $email, 'content' => $content));
	    }
	    return array('form' => $form->createView());
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
