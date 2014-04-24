<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

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
		return array('user' => $this->getUserByLogin($login));
	}

    /**
     * @Route("/user/{login}/modules", name="user_profile_modules")
     * @Template()
     */
    public function modulesAction($login) {
	    return array('user' => $this->getUserByLogin($login));
    }

    /**
     * @Route("/user/{login}/about", name="user_profile_about")
     * @Template()
     */
    public function aboutAction($login) {
		return array('user' => $this->getUserByLogin($login));
    }

	/**
	 * @param $login
	 * @Route("/user/{login}/search", name="user_profile_search")
	 * @Template()
	 */
	public function searchAction($login) {
		return array('user' => $this->getUserByLogin($login));
	}

	private function getUserByLogin($login) {
		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('\Gitbox\Bundle\CoreBundle\Entity\UserAccount')->findOneBy(array('login' => $login));
		if(!$user instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {
			throw $this->createNotFoundException("Nie znaleziono podanego użytkownika");
		}

		return $user;
	}
}
