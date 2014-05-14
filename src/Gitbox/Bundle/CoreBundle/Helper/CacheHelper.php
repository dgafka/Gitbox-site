<?php

namespace Gitbox\Bundle\CoreBundle\Helper;

use Gitbox\Bundle\CoreBundle\Entity\Category;


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

	/** Zwraca login usera, wyszukując go po id
	 * @param $id int
	 * @return String|false
	 */
	public function getUserLoginById($id) {
		$id = (int)($id);

		$userLogin = self::$cache->get($this->getUserIdCache($id));
		if(!$userLogin) {
			$user = self::$entityManager->getRepository('\Gitbox\Bundle\CoreBundle\Entity\UserAccount')->findOneBy(array('id' => $id));
			if($user instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {
				self::$cache->set($this->getUserIdCache($id), $user->getLogin());
				$userLogin = $user->getLogin();
			}
		}

		return $userLogin;
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

    /**
     * Zwraca nazwę kategorii
     *
     * @param int $id
     * @return string|null
     */
    public function getCategoryName($id) {
        $key = $this->getCategoryKey($id);

        $categoryName = self::$cache->get($key);

        if (!$categoryName) {
            $category = self::$entityManager->getRepository('GitboxCoreBundle:Category')->findOneBy(array('id' => $id));

            if ($category instanceof Category) {
                $categoryName = $category->getName();

                self::$cache->set($key, $categoryName);
            }
        }

        return $categoryName;
    }

	/** Zwraca nazwę dostępu do cache-a, dla usera
	 * @param $name
	 * @return String
	 */
	private function getUserCache($name) {
		return 'user/' . $name;
	}

	/** Zwraca nazwę dostępu do cache-a, dla usera
	 * @param $name
	 * @return String
	 */
	private function getUserIdCache($name) {
		return 'userId/' . $name;
	}

    /**
     * Pełna nazwa klucza kategorii
     *
     * @param $id
     * @return String
     */
    private function getCategoryKey($id) {
        return 'category/' . $id;
    }

} 