<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use Gitbox\Bundle\CoreBundle\Entity\UserAccount;
use Gitbox\Bundle\CoreBundle\Entity\UserDescription;
use Gitbox\Bundle\CoreBundle\Form\Type\UserLoginType;
use Gitbox\Bundle\CoreBundle\Form\Type\UserRegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;


class UserAccountController extends Controller
{
    /** Klasa odpowiadająca za formularz do logowania, gdy użytkownik niezalogowany
     * @Template()
     */
    public function indexAction(Request $request)
    {
	    $session = $this->container->get('session');
	    $userAccount = new UserAccount();
	    //Pobranie zmiennych z $_POST i $_GET, zwazywszy na to, ze gubilo parametry
	    $request = Request::createFromGlobals();

	    $form = $this->createForm(new UserLoginType(), $userAccount, array('csrf_protection' => false));
	    $form->handleRequest($request);

	    if(!is_null($session->get('userId'))) {
		    return $this->render('GitboxCoreBundle:UserAccount:index.html.twig', array(
			    'session'   => true,
			    'username'  => $session->get('username'),
		    ));
	    }

	    if($form->isValid()) {

		    /**
		     * @var $helper \Gitbox\Bundle\CoreBundle\Helper\UserAccountHelper
		     */
		    $helper = $this->container->get('user_helper');
		    /**
		     * @param $userGroup \Gitbox\Bundle\CoreBundle\Entity\UserGroup
		     */
		    $userAccount = $helper->instance()->getRepository('\Gitbox\Bundle\CoreBundle\Entity\UserAccount')->findOneBy(array('email' => $userAccount->getEmail(), 'password' => $userAccount->getPassword(), 'status' => 'A'));

		    if($userAccount instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount && $userAccount->getStatus() == 'A') {

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
				$helper->instance()->persist($userAccount);
			    $helper->instance()->flush();

			    return $this->forward('GitboxCoreBundle:Main:index');
		    }

			    $information['type']    = 'warning';
			    $information['content'] = 'Niestety podano błędny login/hasło.';

			    return $this->render('GitboxCoreBundle:UserAccount:index.html.twig', array(
				    'form'          => $form->createView(),
				    'session'       => false,
				    'information'   => $information,
			    ));
	    }

	    return $this->render('GitboxCoreBundle:UserAccount:index.html.twig', array(
		    'form'          => $form->createView(),
		    'session'       => false,
	    ));
    }

	/**
	 * @Template()
	 */
	public function passwordActionMessageAction($user) {
		return array('user' => $user);
	}

    /** Tworzy widok z formularzem do zajerestrownia użytkownika.
     * @Route("register", name="user_register_url")
     * @Template()
     */
    public function registerAction(Request $request)
    {
	    $session = $this->container->get('session');
	    if(!is_null($session->get('username'))){
		    throw $this->createNotFoundException("Nie możesz zarejestrować się bedąc zalogowanym");
	    }

        $userAccount = new UserAccount();

        $form = $this->createForm(new UserRegisterType(), $userAccount);

        $form->handleRequest($request);

        if($form->isValid()) {

	        $message = '';
	        /**
	         * @var $helper \Gitbox\Bundle\CoreBundle\Helper\UserAccountHelper
	         */
	        $helper = $this->container->get('user_helper');
	        $emailUser = $helper->findByEmail($userAccount->getEmail());
	        $loginUser = $helper->findByLogin($userAccount->getLogin());

	        if($emailUser instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {
				$message .= 'Użytkownik o podanym emailu już istnieje. ';
	        }
	        if($loginUser instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {
		        $message .= ' Użytkownik o podanym loginie już istnieje ';
	        }

	        if($message != '') {
		        $information['type']    = 'warning';
		        $information['content'] = $message;

		        return $this->render('GitboxCoreBundle:UserAccount:register.html.twig', array(
			        'form'          => $form->createView(),
			        'session'       => false,
			        'information'   => $information,
		        ));
	        }

	        /**
	         * @var $helper \Gitbox\Bundle\CoreBundle\Helper\UserAccountHelper
	         */
	        $helper = $this->container->get('user_helper');

	        /**
	         * @param $userGroup \Gitbox\Bundle\CoreBundle\Entity\UserGroup
	         */
	        $userGroup = $helper->instance()->getRepository('\Gitbox\Bundle\CoreBundle\Entity\UserGroup')->findOneBy(array('permissions' => 1));
	        $userDescription = new UserDescription();
	        $userDescription->setHit(0);
	        $date = new \DateTime();
	        $userDescription->setRegistrationDate($date);
			$userDescription->setBanDate(null);
			$userDescription->setToken(md5(uniqid(mt_rand(), true)));
	        $userAccount->setStatus('D');

	        $helper->instance()->persist($userDescription);

	        $userAccount->setIdDescription($userDescription);
	        $userAccount->setIdGroup($userGroup);

	        $helper->instance()->persist($userAccount);
	        $helper->instance()->flush();

            return $this->forward('GitboxCoreBundle:Mailer:accountActivation', array(
                'user' => $userAccount
            ));

        }

        return $this->render('GitboxCoreBundle:UserAccount:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

	/** Akcja odpwiedzialna za wylogowanie użytkownika
	 * @Template()
	 * @Route("logout", name="user_logout_url")
	 */
	public function logoutAction(Request $request) {
		$session = $request->getSession();
		if(!is_null($session)) {
			$session->clear();
		}

		$this->redirect($this->generateUrl('home_url'));
	}

    /** Akcja dla okna potwierdzającego rejestrację
     * @Template()
     */
    public function registerSubmitAction($userName)
    {
	    return array('name' => $userName);
    }

	/** Akcja dla odzyskania hasła
	 * @Route("password_recovery", name="user_recover_password_url")
	 * @Template()
	 */
	public function forgottenPasswordAction() {
		return array();
	}

}
