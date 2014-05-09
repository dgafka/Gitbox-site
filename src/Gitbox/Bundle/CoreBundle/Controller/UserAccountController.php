<?php

namespace Gitbox\Bundle\CoreBundle\Controller;

use Doctrine\ORM\EntityNotFoundException;
use Gitbox\Bundle\CoreBundle\Entity\UserAccount;
use Gitbox\Bundle\CoreBundle\Entity\UserDescription;
use Gitbox\Bundle\CoreBundle\Form\Type\UserForgottenPasswordType;
use Gitbox\Bundle\CoreBundle\Form\Type\UserLoginType;
use Gitbox\Bundle\CoreBundle\Form\Type\UserRegisterType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\RedirectResponse;


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

		if ($form->isValid()) {

			$userAccount->setEmail(strtolower($userAccount->getEmail()));
			$userAccount->setPassword(md5($userAccount->getPassword()));

			/**
			 * @var $helper \Gitbox\Bundle\CoreBundle\Helper\UserAccountHelper
			 */
			$helper = $this->container->get('user_helper');
			/**
			 * @param $userGroup \Gitbox\Bundle\CoreBundle\Entity\UserGroup
			 */
			$userAccount = $helper->instance()->getRepository('\Gitbox\Bundle\CoreBundle\Entity\UserAccount')->findOneBy(array('email' => $userAccount->getEmail(), 'password' => $userAccount->getPassword(), 'status' => 'A'));

			if ($userAccount instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount && $userAccount->getStatus() == 'A') {

				$session->set('username', $userAccount->getLogin());
				$session->set('userId', $userAccount->getId());
				$session->set('permission', $userAccount->getIdGroup()->getPermissions());

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

                return array(
                    'session' => true,
                    'username' => $userAccount->getLogin()
                );
			} else if (!$userAccount instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {
                $session = $this->container->get('session');
                $session->getFlashBag()->add('warning', 'Niestety podano błędny login / hasło.');
            } else if ($userAccount->getStatus() != 'A') {
                $session = $this->container->get('session');
                $session->getFlashBag()->add('warning',
                    'Konto nie zostało aktywowane.<br />'.
                    'Aby je aktywować, wejdż na konto e-mail, które podałeś przy rejestracji konta i kliknij w aktywacyjny link.'
                );
            }
		}

		return array(
            'form'          => $form->createView(),
			'session'       => false,
		);
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
				$message .= 'Użytkownik o podanym emailu już istnieje. <br/>';
			}
			if($loginUser instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {
				$message .= ' Użytkownik o podanym loginie już istnieje. <br/>';
			}
			if(strlen($userAccount->getLogin()) < 4) {
				$message .= ' Login powinien posiadać przynajmniej 4 znaki. <br/>';
			}
			if(!preg_match('/^[a-zA-Z0-9]+$/', $userAccount->getLogin())) {
				$message .= ' Login nie powinien posiadać polskich znaków.<br/>';
			}
			if(strlen($userAccount->getPassword()) < 6) {
				$message .= ' Hasło powinno składać się przynajmniej z 6 znaków. <br/>';
			}
			if($message != '') {
                $session = $this->container->get('session');
                $session->getFlashBag()->add('warning', $message);

				return $this->render('GitboxCoreBundle:UserAccount:register.html.twig', array(
					'form'          => $form->createView(),
					'session'       => false
				));
			}

			$userAccount->setEmail(strtolower($userAccount->getEmail()));
			$userAccount->setLogin(strtolower($userAccount->getLogin()));
			$userAccount->setPassword(md5($userAccount->getPassword()));

			/**
			 * @var $helper \Gitbox\Bundle\CoreBundle\Helper\UserAccountHelper
			 */
			$helper = $this->container->get('user_helper');

			//Dodanie użytkownika
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

			//adding modules for user
			/**
			 * @var $cacheHelper \Gitbox\Bundle\CoreBundle\Helper\CacheHelper
			 */
			$cacheHelper = $this->container->get('cache_helper');
			$gitTube     = $this->getDoctrine()->getManager()->getRepository('\Gitbox\Bundle\CoreBundle\Entity\Module')->find($cacheHelper->getModuleIdByName('GitTube'));
			$gitBlog     = $this->getDoctrine()->getManager()->getRepository('\Gitbox\Bundle\CoreBundle\Entity\Module')->find($cacheHelper->getModuleIdByName('GitBlog'));
			$gitDrive    = $this->getDoctrine()->getManager()->getRepository('\Gitbox\Bundle\CoreBundle\Entity\Module')->find($cacheHelper->getModuleIdByName('GitDrive'));



			$this->getDoctrine()->getManager()->persist($userDescription);
			//Ustawienie opisu i grupy dla usera
			$userAccount->setIdDescription($userDescription);
			$userAccount->setIdGroup($userGroup);

			$this->getDoctrine()->getManager()->persist($userAccount);

			//Ustawienie modułów dla usera
			$userModuleGitTube = new \Gitbox\Bundle\CoreBundle\Entity\UserModules();
			$userModuleGitTube->setIdModule($gitTube);
			$userModuleGitBlog = new \Gitbox\Bundle\CoreBundle\Entity\UserModules();
			$userModuleGitBlog->setIdModule($gitBlog);
			$userModuleGitDrive = new \Gitbox\Bundle\CoreBundle\Entity\UserModules();
			$userModuleGitDrive->setIdModule($gitDrive);

			$userModuleGitBlog->setIdUser($userAccount);
			$userModuleGitTube->setIdUser($userAccount);
			$userModuleGitDrive->setIdUser($userAccount);
			$userModuleGitBlog->setStatus('D');
			$userModuleGitDrive->setStatus('D');
			$userModuleGitTube->setStatus('D');

			$this->getDoctrine()->getManager()->persist($userModuleGitTube);
			$this->getDoctrine()->getManager()->persist($userModuleGitBlog);
			$this->getDoctrine()->getManager()->persist($userModuleGitDrive);

			//ustawienie elementów menu, ja pierdole, ale sie rozrosło :D
			$userMenuGitTube = new \Gitbox\Bundle\CoreBundle\Entity\Menu();
			$userMenuGitBlog = new \Gitbox\Bundle\CoreBundle\Entity\Menu();
			$userMenuGitDrive = new \Gitbox\Bundle\CoreBundle\Entity\Menu();

			$userMenuGitTube->setTitle('GitTube');
			$userMenuGitBlog->setTitle('GitBlog');
			$userMenuGitDrive->setTitle('GitDrive');

			$userMenuGitTube->setSort(3);
			$userMenuGitBlog->setSort(2);
			$userMenuGitDrive->setSort(1);

			$userMenuGitTube->setParent(null);
			$userMenuGitBlog->setParent(null);
			$userMenuGitDrive->setParent(null);

			$userMenuGitTube->setIdModule($gitTube);
			$userMenuGitBlog->setIdModule($gitBlog);
			$userMenuGitDrive->setIdModule($gitDrive);

			$userMenuGitTube->setIdUser($userAccount);
			$userMenuGitDrive->setIdUser($userAccount);
			$userMenuGitBlog->setIdUser($userAccount);

			$this->getDoctrine()->getManager()->persist($userMenuGitTube);
			$this->getDoctrine()->getManager()->persist($userMenuGitBlog);
			$this->getDoctrine()->getManager()->persist($userMenuGitDrive);

			$this->getDoctrine()->getManager()->flush();
			return $this->forward('GitboxCoreBundle:Mailer:accountActivation', array(
				'user' => $userAccount
			));

		}

		return $this->render('GitboxCoreBundle:UserAccount:register.html.twig', array(
			'form' => $form->createView(),
		));
	}

	/** Akcja odpwiedzialna za wylogowanie użytkownika
	 * @Route("logout", name="user_logout_url")
	 */
	public function logoutAction(Request $request) {
		$session = $request->getSession();
		if(!is_null($session)) {
			$session->clear();
		}

        // TODO: alert - bezpiecznie wylogowano
        return $this->redirect($this->generateUrl('home_url'));
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
	public function forgottenPasswordAction(Request $request) {
		$session = $this->container->get('session');
		if(!is_null($session->get('username'))){
			throw $this->createNotFoundException("Nie możesz odzyskać hasła bedąc zalogowanym");
		}

		$userAccount = new UserAccount();

		$form = $this->createForm(new UserForgottenPasswordType(), $userAccount, array('csrf_protection' => false));

		$form->handleRequest($request);
		if($form->isValid()) {
			$userHelper = $this->container->get('user_helper');
			$user       = $userHelper->findByEmail($userAccount->getEmail());

			if(!($user instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount)) {
                $session = $this->container->get('session');
                $session->getFlashBag()->add('warning', 'Nie istnieje taki email w bazie.');

				return array('form' => $form->createView());
			}
			$password = uniqid(mt_rand(), true);
			$user->setPassword(md5($password));
			$this->getDoctrine()->getManager()->persist($user);
			$this->getDoctrine()->getManager()->flush();

			return $this->forward('GitboxCoreBundle:Mailer:recoveryPassword', array('user' => $user, 'password' => $password));
		}
		return array('form' => $form->createView());
	}

	/** Akcja dla wyszukiwania użytkownika
	 * @Route("/search/user/{loginPart}", name="search_global")
	 */
	public function searchUser($loginPart) {
		$userHelper = $this->container->get('user_helper');
		$results    = json_encode($userHelper->searchUser($loginPart));

		return new Response($results, 200, array('Content-Type'=>'application/json'));
	}

}
