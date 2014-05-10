<?php

namespace Gitbox\Bundle\CoreBundle\Helper;

use Doctrine\ORM\QueryBuilder;
use Gitbox\Bundle\CoreBundle\Entity\Content;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Abstrakcyjna klasa wspomagająca pobieranie content-ów z bazy
 *
 * Class ContentHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
class IPHelper extends EntityHelper implements CRUDHelper {

	public function __construct($entityManager, $cacheHelper) {
		parent::__construct($entityManager, $cacheHelper);
	}

	/** Wyszukuje obiekt po id
	 * @param $object \Gitbox\Bundle\CoreBundle\Entity\BannedIp|int
	 * @return null|\Gitbox\Bundle\CoreBundle\Entity\BannedIp
	 * @throws \Exception
	 */
	public function find($object)
	{
		$userAccount = null;
		if($object instanceof \Gitbox\Bundle\CoreBundle\Entity\BannedIp) {
			$userAccount = $this->instance()->getRepository('\Gitbox\Bundle\CoreBundle\Entity\BannedIp')->find($object->getId());
		}else if(is_int((int)$object)) {
			$userAccount = $this->instance()->getRepository('\Gitbox\Bundle\CoreBundle\Entity\BannedIp')->find($object);
		}else {
			throw new Exception("Błąd podczas wyszukiwania obiektu.");
		}

		return $userAccount;

	}

	/** Usuwa rekord z tabeli BannedIp.
	 * @param $object \Gitbox\Bundle\CoreBundle\Entity\BannedIp|int
	 * @return \Gitbox\Bundle\CoreBundle\Entity\BannedIp|int
	 * @throws \Exception
	 */
	public function remove($object)
	{
		$userAccount = null;
		if($object instanceof \Gitbox\Bundle\CoreBundle\Entity\BannedIp) {
			$this->instance()->remove($object);
			$this->instance()->flush();
		}else if(is_int($object)) {
			/**
			 * @var $queryBuilder QueryBuilder
			 */
			$queryBuilder = $this->instance()->createQueryBuilder();
			$results = $queryBuilder
				->delete('\Gitbox\Bundle\CoreBundle\Entity\BannedIp', 'ip')
				->where('ip.id = :id')
				->setParameter('id', $object)
				->getQuery()
				->execute()
			;
		}else {
			throw new Exception("Błąd podczas wyszukiwania obiektu.");
		}

		return $userAccount;

	}

	/** Uaktualnia obiekt
	 * @param $object \Gitbox\Bundle\CoreBundle\Entity\BannedIp|int
	 * @throws \Exception
	 * @return \Gitbox\Bundle\CoreBundle\Entity\BannedIp
	 */
	public function update($object)
	{
		if($object instanceof \Gitbox\Bundle\CoreBundle\Entity\BannedIp) {
			$this->instance()->persist($object);
			$this->instance()->flush();
		}else {
			throw new Exception("Błąd podczas uaktualniania obiektu.");
		}

		return $object;
	}

	/** Wstawia obiekt
	 * @param $object \Gitbox\Bundle\CoreBundle\Entity\BannedIp|int
	 * @throws \Exception
	 * @return \Gitbox\Bundle\CoreBundle\Entity\BannedIp
	 */
	public function insert($object)
	{
		if($object instanceof \Gitbox\Bundle\CoreBundle\Entity\BannedIp) {
			$userAccount = $this->instance()->persist($object);
			$this->instance()->flush();
		}else {
			throw new Exception("Błąd podczas dodawania obiektu.");
		}

		return $object;
	}

	/** Zwraca wszystkich userów w danym przedziale
	 * @param $offset
	 * @param $limit
	 * @return mixed
	 */
	public function getIPsByLimit($offset, $limit) {
		/**
		 * @var $queryBuilder \Doctrine\ORM\QueryBuilder
		 */
		$queryBuilder = $this->instance()->createQueryBuilder();

		$results = $queryBuilder
			->select('ua')
			->from('GitboxCoreBundle:BannedIp', 'ua')
			->setFirstResult($offset)
			->setMaxResults($limit)
			->orderBy('ua.createDate')
			->getQuery()
			->execute();

		return $results;
	}


	/** Zwraca ilość wpisów w bazie
	 * @return mixed
	 */
	public function getIPsAmount() {
		/**
		 * @var $queryBuilder \Doctrine\ORM\QueryBuilder
		 */
		$queryBuilder = $this->instance()->createQueryBuilder();

		$amount = $queryBuilder
			->select('count(ua.id)')
			->from('GitboxCoreBundle:BannedIp', 'ua')
			->getQuery()
			->execute();

		return $amount;
	}
}