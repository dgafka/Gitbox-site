<?php
namespace Gitbox\Bundle\CoreBundle\Helper;

use Gitbox\Bundle\CoreBundle\Entity\Content;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Knp\Bundle\PaginatorBundle\Definition\PaginatorAwareInterface;

/**
 * Class DriveContentHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
class DriveContentHelper extends ContentHelper  {



    protected $module = 'GitDrive';


    public function __construct($entityManager, $cacheHelper) {
        parent::__construct($entityManager, $cacheHelper);
    }




    /**
     * Pobieranie menu
     *
     * @param $userLogin
     * @param Request $request
     *
     * @return array | null
     *
     * @throws Exception
     */
    public function getMenuZero($userLogin,  Request & $request = null) {
        if (!isset($this->module)) {
            throw new Exception("Nie zainicjalizowano instancji.");
        }

        $userId = $this->instanceCache()->getUserIdByLogin($userLogin);
        $moduleId = $this->instanceCache()->getModuleIdByName($this->module);

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Menu', 'c')
            ->where('c.idUser = :user_id AND c.idModule = :module_id')
            ->setParameters(array(
                'user_id' => $userId,
                'module_id' => $moduleId
            ));

        if ($request instanceof Request) {
            $query = $queryBuilder->getQuery()->getResult();

            $posts = $query;

            return $posts;
        } else {
            try {
                return $queryBuilder->getQuery()->getResult();
            } catch (NoResultException $e) {
                return null;
            }
        }
    }



    /**
     * Pobieranie contentow
     *
     * @param $userLogin
     * @param int $perPage
     * @param Request $request
     *
     * @return array | null
     *
     * @throws Exception
     */
    public function getContents($userLogin, $perPage = 0, Request & $request = null) {
        if (!isset($this->module)) {
            throw new Exception("Nie zainicjalizowano instancji.");
        }

        $userId = $this->instanceCache()->getUserIdByLogin($userLogin);
        $moduleId = $this->instanceCache()->getModuleIdByName($this->module);

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Content', 'c')
            ->where('c.idUser = :user_id AND c.id_module = :module_id')
            ->orderBy('c.createDate', 'DESC')
            ->setParameters(array(
                'user_id' => $userId,
                'module_id' => $moduleId
            ));

        if ( $request instanceof Request) {
            $query = $queryBuilder->getQuery();

            $posts = $query;

            return $posts;
        } else {
            try {
                return $queryBuilder->getQuery()->getResult();
            } catch (NoResultException $e) {
                return null;
            }
        }
    }
}