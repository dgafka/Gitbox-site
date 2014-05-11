<?php

namespace Gitbox\Bundle\CoreBundle\Helper;


use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class PermissionsHelper {

	/**
	 * @var Session
	 */
	private $session;

	/**
	 * @var EntityManager
	 */
	private $em;

	/**
	 * @var CacheHelper
	 */
	private $cacheHelper;

	public function __construct($session, $entityManager, $cacheHelper) {
		$this->session      = $session;
		$this->em           = $entityManager;
		$this->cacheHelper  = $cacheHelper;
	}

	/** Sprawdza, czy dany użytkownik ma dostęp do aktualnej strony na podstawie sesji
	 * @param $login
	 * @return bool
	 */
	public function checkPermission($login) {

		if(strtolower(trim($this->session->get('username'))) == strtolower(trim($login))) {
			return true;
		}

		return false;
	}

	/** Sprawdza czy zalogowany użytkownik jest adminem
	 * @return bool
	 */
	public function isAdmin() {
		if(!$this->isLogged()) {
			return false;
		}

		if((int)strtolower(trim($this->session->get('permission'))) >= 2) {
			return true;
		}

		return false;
	}

	/** Sprawdza czy zalogowany użytkownik jest głównym adminem
	 * @return bool
	 */
	public function isMainAdmin() {
		if(!$this->isLogged()) {
			return false;
		}

		if((int)strtolower(trim($this->session->get('permission'))) > 2) {
			return true;
		}

		return false;
	}

	/**
	 * Czy użytkownik jest zalogowany
	 * @return bool
	 */
	public function isLogged() {
		if($this->session->get('username')) {
			return true;
		}
		return false;
	}

	/**
	 * Sprawdza, czy aktualny użytkownik jest zbanowany
	 * Jeśli jest nie dopuszcza, do przejscia na jakakolwiek strone.
	 */
	public function checkIfActualUserIpBanned() {
		if(!$this->cacheHelper->instance()->get('ip_managment')) {
			/**
			 * @var $results \Gitbox\Bundle\CoreBundle\Entity\BannedIp[]
			 */
			$results = $this->em->getRepository('GitboxCoreBundle:BannedIp')->findAll();
			foreach($results as $result) {
				$this->cacheHelper->instance()->set($result->getIp(), 'banned');
			}

			$this->cacheHelper->instance()->set('ip_managment', 'working');
		}

		$ip = null;
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		if($this->cacheHelper->instance()->get($ip)) {
			header('HTTP/1.0 403 Forbidden');
			exit('Przepraszamy, ale twoje ip jest zbanowane.');
		}


		/** easter egg  xaxa */
//		if($ip == $ip) {
//			header('HTTP/1.0 403 Forbidden');
//			exit('ip');
//		}
	}

} 