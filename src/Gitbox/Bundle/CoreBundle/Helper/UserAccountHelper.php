<?php
/**
 * Created by PhpStorm.
 * User: daris
 * Date: 4/24/14
 * Time: 3:43 PM
 */

namespace Gitbox\Bundle\CoreBundle\Helper;
use Doctrine\ORM\QueryBuilder;

class UserAccount extends Helper {

	public function find($object)
	{
		$userAccount = null;
		if($object instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {
			$userAccount = $this->instance()->getRepository('\Gitbox\Bundle\CoreBundle\Entity\UserAccount')->find($object->getId());
		}else if(is_int((int)$object)) {
			$userAccount = $this->instance()->getRepository('\Gitbox\Bundle\CoreBundle\Entity\UserAccount')->find($object);
		}

		return $userAccount;

	}

	public function remove($object)
	{
		if($object instanceof \Gitbox\Bundle\CoreBundle\Entity\UserAccount) {
			$userAccount = $this->instance()->remove($object);
		}else if(is_int((int)$object)) {
	    /**
	     * @var $queryBuilder QueryBuilder
	     */
	    $queryBuilder = $this->getDoctrine()->getManager()->createQueryBuilder();
	    $results = $queryBuilder
		    ->delete('\Gitbox\Bundle\CoreBundle\Entity\UserAccount', 'ua')
			->where('ua.id = :id')
		    ->setParameter('ua', $object)
		    ->getQuery()
		    ->execute();
		}

		return $object;

	}

	public function update($object)
	{
		// TODO: Implement update() method.
	}

	public function insert($object)
	{
		// TODO: Implement insert() method.
	}

} 