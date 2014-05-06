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
class DriveContentHelper extends ContentHelper implements PaginatorAwareInterface {

    /**
     * @var Paginator
     */
    protected $paginator;

    protected $module = 'GitDrive';


    public function __construct($entityManager, $cacheHelper) {
        parent::__construct($entityManager, $cacheHelper);
    }

    /**
     * Sets the KnpPaginator instance.
     *
     * @param Paginator $paginator
     *
     * @return PaginatorAware
     */
    public function setPaginator(Paginator $paginator)
    {
        $this->paginator = $paginator;

        return $this;
    }

    /**
     * Returns the KnpPaginator instance.
     *
     * @return Paginator
     */
    public function getPaginator()
    {
        return $this->paginator;
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
            ->innerJoin('c.idMenu', 'm') // automaticaly join keys, upon relation
            ->where('c.idUser = :user_id AND m.idModule = :module_id')
            ->orderBy('c.createDate', 'DESC')
            ->setParameters(array(
                'user_id' => $userId,
                'module_id' => $moduleId
            ));

        if ($perPage > 0 && $request instanceof Request) {
            $query = $queryBuilder->getQuery();

            $posts = $this->paginator->paginate($query, $request->query->get('page', 1), $perPage);

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
     * Pobranie wpisów z bloga użytkownika o podanej kategorii
     *
     * @param $userLogin
     * @param $categoryName
     * @param int $perPage
     * @param Request $request
     *
     * @return array | null
     *
     * @throws Exception
     */
    public function getContentsByCategory($userLogin, $categoryName, $perPage = 0, Request & $request = null) {
        if (!isset($this->module)) {
            throw new Exception("Nie zainicjalizowano instancji.");
        }

        $userId = $this->instanceCache()->getUserIdByLogin($userLogin);
        $moduleId = $this->instanceCache()->getModuleIdByName($this->module);

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Content', 'c')
            ->innerJoin('c.idMenu', 'm') // automaticaly join keys, upon relation
            ->innerJoin('c.idCategory', 'cat') // same here
            ->where('c.idUser = :user_id AND m.idModule = :module_id AND cat.name = :category_name')
            ->orderBy('c.createDate', 'DESC')
            ->setParameters(array(
                'user_id' => $userId,
                'module_id' => $moduleId,
                'category_name' => $categoryName
            ));

        if ($perPage > 0 && $request instanceof Request) {
            $query = $queryBuilder->getQuery();

            $posts = $this->paginator->paginate($query, $request->query->get('page', 1), $perPage);

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
     * Pobranie wpisu z bloga użytkownika
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

        $userId = $this->instanceCache()->getUserIdByLogin($userLogin);
        $moduleId = $this->instanceCache()->getModuleIdByName($this->module);

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('c')
            ->from('GitboxCoreBundle:Content', 'c')
            ->innerJoin('c.idMenu', 'm') // automaticaly join keys, upon relation
            ->where('c.id = :id AND c.idUser = :user_id AND m.idModule = :module_id')
            ->setParameters(array(
                'id' => $id,
                'user_id' => $userId,
                'module_id' => $moduleId
            ));

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}