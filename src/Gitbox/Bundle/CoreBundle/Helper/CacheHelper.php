<?php

namespace Gitbox\Bundle\CoreBundle\Helper;


class CacheHelper {

	/**
	 * @var \Memcache
	 */
	private static $cache;

	/**
	 * @var \Doctrine\Common\Persistence\ObjectManager
	 */
	private static $entityManager;

	public function __construct($entityManager, $host, $port) {
		self::$entityManager = $entityManager;
		self::$cache         = new \Memcache();
		self::$cache->connect($host, $port);
	}

	/** Zwraca instancje cache-a
	 * @return \Memcache
	 */
	public function instance() {
		return self::$cache;
	}

	/** Zwraca id user, wyszukując po jego loginie
	 * @param $login
	 * @return int|false
	 */
	public function getUserIdByLogin($login) {
		$login = trim($login);

		$userId = self::$cache->get($this->getUserCache($login));
		if(!$userId) {
			$user = self::$entityManager->getRepository('\Gitbox\Bundle\CoreBundle\Entity\UserAccount')->findOneBy(array('login' => $login));
			if($user instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {
				self::$cache->set($this->getUserCache($login), $user->getId());
				$userId = $user->getId();
			}
		}

		return $userId;
	}

	/** Zwraca id modułu, wyszukując go po nazwie
	 * @param $name String
	 * @return int|false
	 */
	public function getModuleIdByName($name) {
		$name = trim($name);

		$moduleId = self::$cache->get($name);
		if(!$moduleId) {
			$module = self::$entityManager->getRepository('\Gitbox\Bundle\CoreBundle\Entity\Module')->findOneBy(array('name' => $name));
			if($module instanceof \Gitbox\Bundle\CoreBundle\Entity\Module) {
				self::$cache->set($name, $module->getId());
				$moduleId = $module->getId();
			}
		}

		return $moduleId;
	}

	/** Zwraca nazwę dostępu do cache-a, dla usera
	 * @param $name
	 * @return String
	 */
	private function getUserCache($name) {
		return 'user/' . $name;
	}

} 