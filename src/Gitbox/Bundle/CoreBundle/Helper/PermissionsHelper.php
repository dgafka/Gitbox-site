<?php

namespace Gitbox\Bundle\CoreBundle\Helper;


use Symfony\Component\HttpFoundation\Session\Session;

class PermissionsHelper {

	/**
	 * @var Session
	 */
	private $session;

	public function __construct($session) {
		$this->session = $session;
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

} 