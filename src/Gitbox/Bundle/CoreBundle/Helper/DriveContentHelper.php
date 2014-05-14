<?php
namespace Gitbox\Bundle\CoreBundle\Helper;

use Gitbox\Bundle\CoreBundle\Entity\Content;
use Gitbox\Bundle\CoreBundle\Entity\Menu;
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
     * @return Menu
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
            ->where('c.idUser = :user_id AND c.idModule = :module_id AND c.parent is null')
            ->setMaxResults(1)
            ->setParameters(array(
                'user_id' => $userId,
                'module_id' => $moduleId
            ));

        if ($request instanceof Request) {
            $query = $queryBuilder->getQuery()->getOneOrNullResult();

            $menu = $query;

            return $menu;
        } else {
            try {
                return $queryBuilder->getQuery()->getOneOrNullResult();
            } catch (NoResultException $e) {
                return null;
            }
        }
    }

    /**
     * Pobieranie podmenu
     *
     * @param $menuId
     * @param Request $request
     *
     * @return array | null
     *
     * @throws Exception
     */
    public function getMenus($menuId,  Request & $request = null) {


        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Menu', 'c')
            ->where(' c.parent = :menu_id')
            ->setParameters(array(
                'menu_id' => $menuId,

            ));

        if ($request instanceof Request) {
            $query = $queryBuilder->getQuery()->getResult();

            $menu = $query;

            return $menu;
        } else {
            try {
                return $queryBuilder->getQuery()->getResult();
            } catch (NoResultException $e) {
                return null;
            }
        }

    }


    /**
     * Pobieranie podmenu
     *
     * @param $menuId
     * @param Request $request
     *
     * @return Menu
     *
     * @throws Exception
     */
    public function getMenu($menuId,  Request & $request = null) {


        $queryBuilder = $this->instance()->createQueryBuilder();
        $moduleId = $this->instanceCache()->getModuleIdByName($this->module);

        if (!is_int($menuId)) {
            return null;
        }

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Menu', 'c')
            ->where(' c.id = :menu_id AND c.idModule = :module')
            ->setParameters(array(
                'menu_id' => $menuId,
                'module' => $moduleId,

            ));

        if ($request instanceof Request) {
            $query = $queryBuilder->getQuery()->getOneOrNullResult();

            $menu = $query;

            return $menu;
        } else {
            try {
                return $queryBuilder->getQuery()->getOneOrNullResult();
            } catch (NoResultException $e) {
                return null;
            }
        }

    }

    /**
     * Liczenie menow
     *
     * @param $userId
     * @param Request $request
     *
     * @return int
     *
     * @throws Exception
     */
    public function countMenus($userId, Request & $request = null) {
        $moduleId = $this->instanceCache()->getModuleIdByName($this->module);

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('count(c.id)')
            ->from('GitboxCoreBundle:Menu', 'c')
            ->where(' c.idUser = :user_id AND c.idModule = :module_id')
            ->setParameters(array(
                'user_id' => $userId,
                'module_id'=>$moduleId

            ));

        if ($request instanceof Request) {
            $query = $queryBuilder->getQuery()->getSingleScalarResult();

            $count = $query;

            return $count;
        } else {
            try {
                return $queryBuilder->getQuery()->getSingleScalarResult();
            } catch (NoResultException $e) {
                return null;
            }
        }

    }

    /**
     * Liczenie plikow
     *
     * @param $userId
     * @param Request $request
     *
     * @return int
     *
     * @throws Exception
     */
    public function countAttachments($userId, Request & $request = null) {
        $moduleId = $this->instanceCache()->getModuleIdByName($this->module);

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('count(c.id)')
            ->from('GitboxCoreBundle:Attachment', 'c')
            ->innerJoin('c.idContent', 'm') // automaticaly join keys, upon relation
            ->innerJoin('m.idMenu', 'r') // automaticaly join keys, upon relation
            ->where(' m.idUser = :user_id AND r.idModule = :module_id')
            ->setParameters(array(
                'user_id' => $userId,
                'module_id'=>$moduleId

            ));

        if ($request instanceof Request) {
            $query = $queryBuilder->getQuery()->getSingleScalarResult();

            $count = $query;

            return $count;
        } else {
            try {
                return $queryBuilder->getQuery()->getSingleScalarResult();
            } catch (NoResultException $e) {
                return null;
            }
        }

    }

    /**
     * Liczenie plikow
     *
     * @param $menuId
     * @param Request $request
     *
     * @return int
     *
     * @throws Exception
     */
    public function countContentAttachments($menuId, Request & $request = null) {
        $moduleId = $this->instanceCache()->getModuleIdByName($this->module);

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('count(c.id)')
            ->from('GitboxCoreBundle:Attachment', 'c')
            ->where(' m.idMenu = :menu_id ')
            ->setParameters(array(
                'menu_id' => $menuId

            ));

        if ($request instanceof Request) {
            $query = $queryBuilder->getQuery()->getSingleScalarResult();

            $count = $query;

            return $count;
        } else {
            try {
                return $queryBuilder->getQuery()->getSingleScalarResult();
            } catch (NoResultException $e) {
                return null;
            }
        }

    }

    /**
     * Pobieranie contentu
     *
     * @param $menuId
     * @param Request $request
     *
     * @return array | null
     *
     * @throws Exception
     */
    public function getMenuContent($menuId,  Request & $request = null) {


        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Content', 'c')
            ->where(' c.idMenu = :menu_id')
            ->setParameters(array(
                'menu_id' => $menuId,

            ));

        if ($request instanceof Request) {
            $query = $queryBuilder->getQuery()->getResult();

            $content= $query;

            return $content;
        } else {
            try {
                return $queryBuilder->getQuery()->getResult();
            } catch (NoResultException $e) {
                return null;
            }
        }

    }

    /**
     * Pobieranie contentu
     *
     * @param $contentId
     * @param Request $request
     *
     * @return Content
     *
     * @throws Exception
     */

    public function getContent($contentId,  Request & $request = null) {


        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Content', 'c')
            ->where(' c.id = :content_id')
            ->setMaxResults(1)
            ->setParameters(array(
                'content_id' => $contentId,

            ));

        if ($request instanceof Request) {
            $query = $queryBuilder->getQuery()->getOneOrNullResult();

            $content= $query;

            return $content;
        } else {
            try {
                return $queryBuilder->getQuery()->getOneOrNullResult();
            } catch (NoResultException $e) {
                return null;
            }
        }

    }


    /**
     * Pobieranie contentu
     *
     * @param $contentId
     * @param Request $request
     *
     * @return array/null
     *
     * @throws Exception
     */

    public function getAttachments($contentId,  Request & $request = null) {


        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Attachment', 'c')
            ->where(' c.idContent = :content_id')
            ->setParameters(array(
                'content_id' => $contentId,

            ));

        if ($request instanceof Request) {
            $query = $queryBuilder->getQuery()->getResult();

            $content= $query;

            return $content;
        } else {
            try {
                return $queryBuilder->getQuery()->getResult();
            } catch (NoResultException $e) {
                return null;
            }
        }

    }

}