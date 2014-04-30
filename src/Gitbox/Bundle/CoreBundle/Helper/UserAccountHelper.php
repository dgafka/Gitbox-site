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

	public function __construct($entityManager) {
		parent::__construct($entityManager);
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



	/** Usuwa rekord z tabeli User Account po id
	 * @param $object \Gitbox\Bundle\CoreBundle\Entity\UserAccount|int
	 * @return \Gitbox\Bundle\CoreBundle\Entity\UserAccount|int
	 * @throws \Exception
	 */
	public function remove($object)
	{
		if($object instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {
			$userAccount = $this->instance()->remove($object);
		}else if(is_int($object)) {
		    /**
		     * @var $queryBuilder QueryBuilder
		     */
		    $queryBuilder = $this->instance()->createQueryBuilder();
		    $queryBuilder
			    ->delete('\Gitbox\Bundle\CoreBundle\Entity\UserAccount', 'ua')
				->where('ua.id = :id')
			    ->setParameter('id', $object)
			    ->getQuery()
			    ->execute();
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
			$userAccount = $this->instance()->persist($object);
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

} 