<?php

namespace Gitbox\Bundle\CoreBundle\Helper;

use Gitbox\Bundle\CoreBundle\Entity\Content;
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
		    ->select('a')
		    ->from('GitboxCoreBundle:Content', 'c')
		    ->innerJoin('GitboxCoreBundle:Attachment', 'a', 'WITH', 'a.idContent = c.id')
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

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Content', 'c')
            ->innerJoin('GitboxCoreBundle:UserAccount', 'ua', JOIN::WITH, 'c.idUser = ua.id')
            ->innerJoin('c.idMenu', 'menu')
            ->innerJoin('menu.idModule', 'm')
            ->where('c.id = :id AND ua.login = :login AND m.name = :module')
            ->setParameters(array(
                'id' => $id,
                'login' => $userLogin,
                'module' => $this->module
            ));

        try {
            return $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }


}