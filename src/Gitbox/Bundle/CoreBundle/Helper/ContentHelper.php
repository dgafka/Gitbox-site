<?php

namespace Gitbox\Bundle\CoreBundle\Helper;

use Gitbox\Bundle\CoreBundle\Entity\Content;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Abstrakcyjna klasa wspomagająca pobieranie content-ów z bazy
 *
 * Class ContentHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
abstract class ContentHelper extends EntityHelper implements CRUDHelper {

    /**
     * Nazwa modułu w bazie danych
     *
     * @var String
     */
    protected $module;

    public function __construct($entityManager, $cacheHelper) {
        parent::__construct($entityManager, $cacheHelper);
    }

    /**
     * Ustawia nazwę modułu dla instancji helper-a
     *
     * @param $moduleName
     */
    public function init($moduleName) {
        $this->module = $moduleName;
    }

    /**
     * @param mixed $entity
     * @return Content
     * @throws Exception
     */
    public function find($entity) {
        $repository = $this->instance()->getRepository('GitboxCoreBundle:Content');

        if ($entity instanceof Content) {
            $content = $repository->find($entity->getId());
        } else if (is_int($entity)) {
            $content = $repository->find($entity);
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }

        return $content;
    }

    /**
     * @return array
     */
    public function findAll() {
        $contents = $this->instance()
            ->getRepository('GitboxCoreBundle:Content')
            ->findAll();

        return $contents;
    }

    /**
     * @param mixed $content
     * @throws Exception
     */
    public function remove($content) {
        if ($content instanceof Content) {
            $this->instance()->remove($content);
            $this->instance()->flush();
        } else if (is_int($content)) {
            $queryBuilder = $this->instance()->createQueryBuilder();
            $queryBuilder
                ->delete('GitboxCoreBundle:Content', 'c')
                ->where('c.id = :id')
                ->setParameter('id', $content)
                ->getQuery()
                ->execute();
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }
    }

    /**
     * @param $content Content
     * @throws Exception
     */
    public function update($content) {
        if ($content instanceof Content) {
            $this->instance()->persist($content);
            $this->instance()->flush();
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }
    }

    /**
     * @param array $entities
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    public function updateArray($entities) {
        if (is_array($entities) && !empty($entities)) {
            for ($i = 0; $i < count($entities); $i++) {
                if ($entities[$i] instanceof Content) {
                    $this->instance()->persist($entities[$i]);
                } else {
                    throw new Exception('Niepoprawny typ parametru przy indeksie [' . $i . '].');
                }
            }

            $this->instance()->flush($entities);
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }
    }

    /**
     * @param $content Content
     * @throws Exception
     */
    public function insert($content) {
        if ($content instanceof Content) {
            $this->instance()->persist($content);
            $this->instance()->flush();
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }
    }

    /**
     * @param int $id
     */
    public function updateOneHits($id) {
        if (is_int($id)) {
            $queryBuilder = $this->instance()->createQueryBuilder();

            $queryBuilder
                ->update('GitboxCoreBundle:Content', 'c')
                ->set('c.hit', 'c.hit + 1')
                ->where('c.id = :id')
                ->setParameter('id', $id)
                ->getQuery()->execute();
        }
    }

    /**
     * @param array $ids
     */
    public function updateHits($ids) {
        if (!empty($ids)) {
            $queryBuilder = $this->instance()->createQueryBuilder();

            $queryBuilder
                ->update('GitboxCoreBundle:Content', 'c')
                ->set('c.hit', 'c.hit + 1')
                ->where('c.id IN (:ids)')
                ->setParameter('ids', $ids)
                ->getQuery()->execute();
        }
    }
}