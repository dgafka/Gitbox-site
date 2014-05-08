<?php

namespace Gitbox\Bundle\CoreBundle\Helper;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Config\Definition\Exception\Exception;

/** Klasa wspomagająca User Account
 * Class UserAccountHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
class UserAccountHelper extends EntityHelper implements CRUDHelper {

	public function __construct($entityManager, $cacheHelper) {
		parent::__construct($entityManager, $cacheHelper);
	}

	/** Wyszukuje obiekt po id
	 * @param $object \Gitbox\Bundle\CoreBundle\Entity\UserAccount|int
	 * @return null|\Gitbox\Bundle\CoreBundle\Entity\UserAccount
	 * @throws \Exception
	 */
	public function find($object)
	{
		$userAccount = null;
		if($object instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {
			$userAccount = $this->instance()->getRepository('\Gitbox\Bundle\CoreBundle\Entity\UserAccount')->find($object->getId());
		}else if(is_int((int)$object)) {
			$userAccount = $this->instance()->getRepository('\Gitbox\Bundle\CoreBundle\Entity\UserAccount')->find($object);
		}else {
			throw new Exception("Błąd podczas wyszukiwania obiektu.");
		}

		return $userAccount;

	}

	/** Znajduje jeden obiekt po loginie
	 * @param $login
	 * @return \Gitbox\Bundle\CoreBundle\Entity\UserAccount
	 */
	public function findByLogin($login) {
		$userAccount = $this->instance()->getRepository('\Gitbox\Bundle\CoreBundle\Entity\UserAccount')->findOneBy(array('login' => $login));
		return $userAccount;
	}

	/**
	 * @param $token
	 * @return \Gitbox\Bundle\CoreBundle\Entity\UserAccount|array
	 */
	public function findByToken($token) {

		/**
		 * @var $queryBuilder QueryBuilder
		 */
		$queryBuilder = $this->instance()->createQueryBuilder();
		$results = $queryBuilder
			->select('ua')
			->from('\Gitbox\Bundle\CoreBundle\Entity\UserAccount', 'ua')
			->innerJoin('\Gitbox\Bundle\CoreBundle\Entity\UserDescription', 'ud', 'WITH', 'ud.id = ua.idDescription')
			->where('ud.token = :token')
			->setParameter('token', $token)
			->getQuery()
			->execute()
		;

		if(count($results) == 1) {
			$results = $results[0];
		}

		return $results;
	}

	/** Szuka użytkownika po emailu
	 * @param $email
	 * @return \Gitbox\Bundle\CoreBundle\Entity\UserAccount
	 */
	public function findByEmail($email) {
		/**
		 * @var $queryBuilder QueryBuilder
		 */
		$queryBuilder = $this->instance()->createQueryBuilder();
		$results = $queryBuilder
			->select('ua')
			->from('\Gitbox\Bundle\CoreBundle\Entity\UserAccount', 'ua')
			->where('ua.email = :email')
			->setParameter('email', $email)
			->getQuery()
			->execute()
		;

		if(count($results) == 1) {
			$results = $results[0];
		}

		return $results;
	}



	/** Usuwa rekord z tabeli User Account oraz wszystkie jego powiązania, włącznie z menu i contentami.
	 * @param $object \Gitbox\Bundle\CoreBundle\Entity\UserAccount|int
	 * @return \Gitbox\Bundle\CoreBundle\Entity\UserAccount|int
	 * @throws \Exception
	 */
	public function remove($object)
	{
		if(is_int((int)$object)) {
			$object = $this->find($object);
		}

		if($object instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {

			//User modules
			$modules = $this->instance()->getRepository('GitboxCoreBundle:UserModules')->findBy(array('idUser' => $object->getId()));
			foreach($modules as $module) {
				$this->instance()->remove($module);
			}
			//Menus
			$menus = $this->instance()->getRepository('GitboxCoreBundle:Menu')->findBy(array('idUser' => $object->getId()));
			foreach($menus as $menu) {
				//Contents
				$contents = $this->instance()->getRepository('GitboxCoreBundle:Content')->findBy(array('idMenu' => $menu->getId()));
				foreach($contents as $content) {
					$this->instance()->remove($content);
				}
				$this->instance()->remove($menu);
			}
			//User account
			$this->instance()->remove($object);

			$this->instance()->flush();
		}else {
			throw new Exception("Błąd podczas usuwania obiektu.");
		}

		return $object;

	}

	/** Uaktualnia obiekt
	 * @param $object \Gitbox\Bundle\CoreBundle\Entity\UserAccount|int
	 * @throws \Exception
	 * @return \Gitbox\Bundle\CoreBundle\Entity\UserAccount
	 */
	public function update($object)
	{
		if($object instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {
			$this->instance()->persist($object);
			$this->instance()->flush();
		}else {
			throw new Exception("Błąd podczas uaktualniania obiektu.");
		}

		return $object;
	}

	/** Wstawia obiekt
	 * @param $object \Gitbox\Bundle\CoreBundle\Entity\UserAccount|int
	 * @throws \Exception
	 * @return \Gitbox\Bundle\CoreBundle\Entity\UserAccount
	 */
	public function insert($object)
	{
		if($object instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {
			$userAccount = $this->instance()->persist($object);
			$this->instance()->flush();
		}else {
			throw new Exception("Błąd podczas dodawania obiektu.");
		}

		return $object;
	}

	/** Wyszukuje użytkowników po części loginu
	 * @param $loginPart
	 * @return array
	 */
	public function searchUser($loginPart) {
		/**
		 * @var $queryBuilder \Doctrine\ORM\QueryBuilder
		 */
		$queryBuilder = $this->instance()->createQueryBuilder();

		$results = $queryBuilder
			->select('ua.login')
			->from('GitboxCoreBundle:UserAccount', 'ua')
			->where('lower( ua.login ) LIKE :login')
			->setParameter('login', '%' . trim(strtolower($loginPart)) . '%')
			->setMaxResults(10)
			->getQuery()
			->execute();

		return $results;
	}

	/** Zwraca wszystkich userów w danym przedziale
	 * @param $offset
	 * @param $limit
	 * @return mixed
	 */
	public function getUsersByLimit($offset, $limit) {
		/**
		 * @var $queryBuilder \Doctrine\ORM\QueryBuilder
		 */
		$queryBuilder = $this->instance()->createQueryBuilder();

		$results = $queryBuilder
			->select('ua')
			->from('GitboxCoreBundle:UserAccount', 'ua')
			->setFirstResult($offset)
			->setMaxResults($limit)
			->orderBy('ua.login')
			->getQuery()
			->execute();

		return $results;
	}


	/** Zwraca ilość użytkowników w bazie
	 * @return mixed
	 */
	public function getUsersAmount() {
		/**
		 * @var $queryBuilder \Doctrine\ORM\QueryBuilder
		 */
		$queryBuilder = $this->instance()->createQueryBuilder();

		$amount = $queryBuilder
			->select('count(ua.id)')
			->from('GitboxCoreBundle:UserAccount', 'ua')
			->getQuery()
			->execute();

		return $amount;
	}

	/** Zmienia status użytkownika
	 * @param $id
	 * @param $status
	 */
	public function changeStatus($id, $status) {
		$user = $this->find($id)->setStatus($status);
		$this->update($user);
	}

	/** Zmienia poziom adminsistratora
	 * @param $id
	 * @param $permission
	 */
	public function changePermission($id, $permission) {
		$user      = $this->find($id);
		$userGroup = $this->instance()->getRepository('GitboxCoreBundle:UserGroup')->findOneBy(array('permissions' => $permission));
		$user->setIdGroup($userGroup);

		$this->update($user);
	}

} 