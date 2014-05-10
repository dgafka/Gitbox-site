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