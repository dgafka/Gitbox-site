<?php

namespace Gitbox\Bundle\CoreBundle\Helper;

use Gitbox\Bundle\CoreBundle\Entity\Menu;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Klasa serwisowa, do pobierania rekordów encji Menu, z bazy
 *
 * Class MenuHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
class MenuHelper extends EntityHelper implements CRUDHelper {

    public function __construct($entityManager, $cacheHelper) {
        parent::__construct($entityManager, $cacheHelper);
    }

    /**
     * @param mixed $entity
     *
     * @return Menu
     *
     * @throws Exception
     */
    public function find($entity) {
        $repository = $this->instance()->getRepository('GitboxCoreBundle:Menu');

        if ($entity instanceof Menu) {
            $menu = $repository->find($entity->getId());
        } else if (is_int($entity)) {
            $menu = $repository->find($entity);
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }

        return $menu;
    }

    /**
     * Zwraca obiekt encji Menu z bazy, na podstawie parametrów: użytkownik oraz moduł
     *
     * @param int|string $user
     * @param int|string $module
     *
     * @return array | null
     *
     * @throws Exception
     */
    public function findByUserAndModule($user, $module) {
        // walidacja parametrów
        if (is_string($user)) {
            $userId = $this->instanceCache()->getUserIdByLogin($user);
        } else if (is_int($user)) {
            $userId = $user;
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }

        // walidacja parametrów
        if (is_string($module)) {
            $moduleId = $this->instanceCache()->getModuleIdByName($module);
        } else if (is_int($module)) {
            $moduleId = $module;
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }

        $repository = $this->instance()->getRepository('GitboxCoreBundle:Menu');

        $menu = $repository->findOneBy(array(
            'idUser' => $userId,
            'idModule' => $moduleId
        ));

        return $menu;
    }

    /**
     * @return array
     */
    public function findAll() {
        $menus = $this->instance()
            ->getRepository('GitboxCoreBundle:Menu')
            ->findAll();

        return $menus;
    }

    /**
     * @param mixed $menu
     *
     * @throws Exception
     */
    public function remove($menu) {
        if ($menu instanceof Menu) {
            $this->instance()->remove($menu);
            $this->instance()->flush();
        } else if (is_int($menu)) {

            $queryBuilder = $this->instance()->createQueryBuilder();
            $queryBuilder
                ->delete('GitboxCoreBundle:Content', 'c')
                ->where('c.idMenu = :id')
                ->setParameter('id', $menu)
                ->getQuery()
                ->execute();
            $queryBuilder = $this->instance()->createQueryBuilder();
            $queryBuilder
                ->delete('GitboxCoreBundle:Menu', 'c')
                ->where('c.id = :id')
                ->setParameter('id', $menu)
                ->getQuery()
                ->execute();
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }
    }

    /**
     * @param $menu Menu
     *
     * @throws Exception
     */
    public function update($menu) {
        if ($menu instanceof Menu) {
            $this->instance()->persist($menu);
            $this->instance()->flush();
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }
    }

    /**
     * @param $menu Menu
     *
     * @throws Exception
     */
    public function insert($menu) {
        if ($menu instanceof Menu) {
            $this->instance()->persist($menu);
            $this->instance()->flush();
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }
    }
}