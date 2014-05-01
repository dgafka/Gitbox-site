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

//	    $queryBuilder
//		    ->select('c, a')
//		    ->from('GitboxCoreBundle:Content', 'c')
//		    ->innerJoin('GitboxCoreBundle:Attachment', 'Attachment', 'a', 'WITH', 'a.idContent = c.id')
//		    ->innerJoin('GitboxCoreBundle', )

        $queryBuilder
            ->select('c, a')
            ->from('GitboxCoreBundle:Content', 'c')
            ->innerJoin('GitboxCoreBundle:UserAccount', 'ua', JOIN::WITH, 'c.idUser = ua.id')
            ->innerJoin('c.idMenu', 'menu')
            ->innerJoin('menu.idModule', 'm')
            ->innerJoin('GitboxCoreBundle:Attachment','a', JOIN::WITH, 'a.idContent = c.id')
            ->where('ua.login = :login AND m.name = :module')
            ->setParameters(array(
                'login' => $userLogin,
                'module' => $this->module
            ));
/*        SELECT c.*, a.* FROM content c
        INNER JOIN user_account ua ON ua.id=c.id_user
        INNER JOIN menu men ON men.id=c.id_menu
        INNER JOIN module mod ON mod.id=men.id_module
        INNER JOIN attachment a ON a.id_content=c.id
        where mod.name='GitTube' AND ua.login='test2';
*/

        try {
            return $queryBuilder->getQuery()->getResult();
        } catch (NoResultException $e) {
            return null;
        }
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