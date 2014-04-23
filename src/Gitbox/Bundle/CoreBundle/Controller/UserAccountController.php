<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Gitbox\Bundle\CoreBundle\Entity\UserAccount;
use Gitbox\Bundle\CoreBundle\Entity\UserDescription;
use Gitbox\Bundle\CoreBundle\Form\Type\UserAccountType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class UserAccountController extends Controller
{
    /**
     * @Template()
     */
    public function indexAction()
    {
        return array();
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
	        $date = new \DateTime('10/22/2013');
	        $date = $date->format('Y-m-d H:i:s');
	        $userDescription->setRegistrationDate($date);

	        $userAccount->setStatus('A');
	        /**
	         * @TODO email verification, ustawic status na 'D' i dopiero po wpisaniu tokena zmienic status na A
	         * $userDescription->setToken()
	         */

	        $em->persist($userDescription);

	        $userAccount->setIdDescription($userDescription);
	        $userAccount->setIdGroup($userGroup);
			/** Wystepuje blad nie wiem czemu narazie. */
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

    /**
     * @Template()
     */
    public function registerSubmitAction($userName)
    {
	    return array('name' => $userName);
    }

    /**
     * @Route("user/login")
     * @Template()
     */
    public function loginAction()
    {
    }

    /**
     * @Route("user/login/submit")
     * @Template()
     */
    public function loginSubmitAction()
    {
    }

    /**
     * @Route("user/{login}")
     * @Template()
     */
    public function showAction($login) {
        $user['login'] = $login;

        return array('user' => $user);
    }


}
