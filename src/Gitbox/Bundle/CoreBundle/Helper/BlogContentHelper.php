<?php

namespace Gitbox\Bundle\CoreBundle\Helper;

use Gitbox\Bundle\CoreBundle\Entity\Content;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Class BlogContentHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
class BlogContentHelper extends ContentHelper {

    protected $module = 'blog';

    public function __construct($entityManager) {
        parent::__construct($entityManager);
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

        try {
            $queryBuilder->getQuery()->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * Pobranie wpisów z bloga użytkownika o podanej kategorii
     *
     * @param $userLogin
     * @param $categoryName
     *
     * @return Content | null
     *
     * @throws Exception
     */
    public function getContentsByCategory($userLogin, $categoryName = null) {
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
            ->innerJoin('c.idCategory', 'cat')
            ->where('ua.login = :login AND m.name = :module AND cat.name = :category')
            ->setParameters(array(
                'login' => $userLogin,
                'module' => $this->module,
                'category' => $categoryName
            ));

        try {
            $queryBuilder->getQuery()->getResult();
        } catch (NoResultException $e) {
            return null;
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
            $queryBuilder->getQuery()->getResult();
        } catch (NoResultException $e) {
            return null;
        }
    }
}