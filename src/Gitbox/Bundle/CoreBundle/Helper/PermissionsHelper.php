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
} 