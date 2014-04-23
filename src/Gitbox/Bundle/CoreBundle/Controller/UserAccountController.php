<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Gitbox\Bundle\CoreBundle\Entity\UserAccount;
use Gitbox\Bundle\CoreBundle\Entity\UserDescription;
use Gitbox\Bundle\CoreBundle\Form\Type\UserAccountLoginType;
use Gitbox\Bundle\CoreBundle\Form\Type\UserAccountType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;


class UserAccountController extends Controller
{
    /** Klasa odpowiadająca za formularz do logowania, gdy użytkownik niezalogowany
     * @Template()
     */
    public function indexAction(Request $request)
    {
	    $session = $request->getSession();
		if(is_null($session)) {
			$session = new Session();
			$session->start();
			$request->setSession($session);
		}
	    $userAccount = new UserAccount();
	    //Pobranie zmiennych z $_POST i $_GET, zwazywszy na to, ze gubilo parametry
	    $request = Request::createFromGlobals();

	    $form = $this->createForm(new UserAccountLoginType(), $userAccount);
	    $form->handleRequest($request);

	    if(!is_null($session->get('userId'))) {
		    return $this->render('GitboxCoreBundle:UserAccount:index.html.twig', array(
			    'session' => true,
		    ));
	    }

	    if($form->isValid()) {
		    $em = $this->getDoctrine()->getManager();
		    /**
		     * @param $userGroup \Gitbox\Bundle\CoreBundle\Entity\UserGroup
		     */
		    $userAccount = $em->getRepository('\Gitbox\Bundle\CoreBundle\Entity\UserAccount')->findOneBy(array('email' => $userAccount->getEmail(), 'password' => $userAccount->getPassword(), 'status' => 'A'));

		    if($userAccount instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {

				$session->set('username', $userAccount->getLogin());
			    $session->set('userId', $userAccount->getId());

			    $ip = null;
			    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
				    $ip = $_SERVER['HTTP_CLIENT_IP'];
			    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			    } else {
				    $ip = $_SERVER['REMOTE_ADDR'];
			    }
			    $userAccount->getIdDescription()->setIp($ip);
				$em->persist($userAccount);
			    $em->flush();

			    return $this->render('GitboxCoreBundle:UserAccount:index.html.twig', array(
				    'session' => true,
			    ));
		    }
	    }

	    return $this->render('GitboxCoreBundle:UserAccount:index.html.twig', array(
		    'form' => $form->createView(),
		    'session' => false,
	    ));
    }

    /** Tworzy widok z formularzem do zajerestrownia użytkownika.
     * @Route("user/register")
     * @Template()
     */
    public function registerAction(Request $request)
    {
        $userAccount = new UserAccount();

        $form = $this->createForm(new UserAccountType(), $userAccount);

        $form->handleRequest($request);

        if($form->isValid()) {

	        $em = $this->getDoctrine()->getManager();
	        /**
	         * @param $userGroup \Gitbox\Bundle\CoreBundle\Entity\UserGroup
	         */
	        $userGroup = $em->getRepository('\Gitbox\Bundle\CoreBundle\Entity\UserGroup')->findOneBy(array('permissions' => 1));
	        $userDescription = new UserDescription();
	        $userDescription->setHit(1);
	        $date = new \DateTime();
	        $userDescription->setRegistrationDate($date);
			$userDescription->setBanDate(null);

	        $userAccount->setStatus('A');
	        /**
	         * @TODO email verification, ustawic status na 'D' i dopiero po wpisaniu tokena zmienic status na A
	         * $userDescription->setToken()
	         */

	        $em->persist($userDescription);

	        $userAccount->setIdDescription($userDescription);
	        $userAccount->setIdGroup($userGroup);

	        $em->persist($userAccount);
	        $em->flush();

            return $this->forward('GitboxCoreBundle:UserAccount:registerSubmit', array(
                'userName' => $userAccount->getLogin()
            ));

        }

        return $this->render('GitboxCoreBundle:UserAccount:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

	/** Akcja odpwiedzialna za wylogowanie użytkownika
	 * @Template()
	 * @Route("user/logout")
	 */
	public function logoutAction(Request $request) {
		$session = $request->getSession();
		if(!is_null($session)) {
			$session->clear();
		}

		$this->redirect($this->generateUrl('home_url'));
	}

    /**
     * @Template()
     */
    public function registerSubmitAction($userName)
    {
	    return array('name' => $userName);
    }

	/**
	 * @Route("user/getMyPasswordBack")
	 * @Template()
	 */
	public function forgottenPasswordAction() {
		return array();
	}

    /**
     * @Route("user/{login}", name="user_profile_url")
     * @Template()
     */
    public function showAction($login) {
        $user['login'] = $login;

        return array('user' => $user);
    }


}
