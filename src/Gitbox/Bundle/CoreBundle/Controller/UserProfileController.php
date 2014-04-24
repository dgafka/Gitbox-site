<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class UserProfileController extends Controller
{

	/** Akcja dla ukazania profilu usera
	 * @Route("user/{login}", name="user_profile_url")
	 * @Template()
	 */
	public function indexAction($login) {

		$em = $this->getDoctrine()->getManager();
		$user = $em->getRepository('\Gitbox\Bundle\CoreBundle\Entity\UserAccount')->findOneBy(array('login' => $login));
		if(!$user instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {
			throw $this->createNotFoundException("Nie znaleziono podanego użytkownika");
		}

		return array('user' => $user);
	}

    /**
     * @Route("/user/{user}/modules")
     * @Template()
     */
    public function modulesAction($user)
    {

    }

    /**
     * @Route("/user/{user}/about")
     * @Template()
     */
    public function aboutAction($user)
    {
    }

}
