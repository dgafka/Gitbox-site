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

    public function __construct($entityManager) {
        parent::__construct($entityManager);

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

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Content', 'c')
            ->innerJoin('GitboxCoreBundle:UserAccount', 'ua', JOIN::WITH, 'c.idUser = ua.id')
            ->innerJoin('c.idMenu', 'menu')
            ->innerJoin('menu.idModule', 'm')
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


}