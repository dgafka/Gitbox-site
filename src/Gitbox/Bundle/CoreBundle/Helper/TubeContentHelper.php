<?php

namespace Gitbox\Bundle\CoreBundle\Helper;

use Gitbox\Bundle\CoreBundle\Entity\Content;
use Gitbox\Bundle\CoreBundle\Entity\Attachment;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Config\Definition\Exception\Exception;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Class TubeContentHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
class TubeContentHelper extends ContentHelper {

    protected $module = 'GitTube';

    public function __construct($entityManager, $cacheHelper) {
        parent::__construct($entityManager, $cacheHelper);

        $this->module = 'GitTube';
    }

    /**
     * Pobranie wpisów z bloga użytkownika
     *
     * @param $userLogin
     *
     * @return Content | null
     *
     * @throws Exception
     */
    public function getContents($userLogin) {
        if (!isset($this->module)) {
            throw new Exception("Nie zainicjalizowano instancji.");
        }
		$userId    = $this->instanceCache()->getUserIdByLogin($userLogin);
		$gitTubeId = $this->instanceCache()->getModuleIdByName('GitTube');

        $queryBuilder = $this->instance()->createQueryBuilder();

	    $queryBuilder
		    ->select('c')
		    ->from('GitboxCoreBundle:Content', 'c')
		    /*->innerJoin('GitboxCoreBundle:Attachment', 'a', 'WITH', 'a.idContent = c.id')*/
		    ->innerJoin('GitboxCoreBundle:Menu', 'm', 'WITH','m.id = c.idMenu')
		    ->where('m.idUser = :userId')
		    ->andWhere('m.idModule = :moduleId')

		    ->setParameters(array(
			   'userId'     => $userId,
			   'moduleId'   => $gitTubeId
		    ));

        $results = $queryBuilder->getQuery()->getResult();

	    return $results;
    }
    /**
     * Pobranie jednego filmu z bazy
     *
     * @param int $id
     * @param $userLogin
     *
     * @return Content | null
     *
     * @throws Exception
     */
    public function getOneContent($id, $userLogin) {
        if (!isset($this->module)) {
            throw new Exception("Nie zainicjalizowano instancji.");
        }

        $userId    = $this->instanceCache()->getUserIdByLogin($userLogin);
        $gitTubeId = $this->instanceCache()->getModuleIdByName('GitTube');
        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('a')
            ->from('GitboxCoreBundle:Content', 'c')
            ->innerJoin('GitboxCoreBundle:Attachment', 'a', 'WITH', 'a.idContent = c.id')
            ->innerJoin('GitboxCoreBundle:Menu', 'm', 'WITH','m.id = c.idMenu')
            ->where('m.idUser = :userId')
            ->andWhere('m.idModule = :moduleId')
            ->andWhere('c.id = :contentId')
            ->setParameters(array(
                'userId' => $userId,
                'moduleId' => $gitTubeId,
                'contentId' => $id
            ));

        try {
            return $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @param $object \Gitbox\Bundle\CoreBundle\Entity\Attachment
     * @return null|\Gitbox\Bundle\CoreBundle\Entity\Attachment
     * @throws \Exception
     */
    public function insertIntoAttachment($object) {

            if($object instanceof \Gitbox\Bundle\CoreBundle\Entity\Attachment) {
                $newAttachemnt = $this->instance()->persist($object);
                $this->instance()->flush();
            }else {
                throw new Exception("Błąd podczas dodawania obiektu.");
            }

            return $object;

    }
    public function findIdMenuByName($name) {
        if (!isset($this->module)) {
            throw new Exception("Nie zainicjalizowano instancji.");
        }
        $gitTubeId = $this->instanceCache()->getModuleIdByName($name);
        return $gitTubeId;
    }
}