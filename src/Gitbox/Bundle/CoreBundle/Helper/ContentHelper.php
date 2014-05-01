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
     * @param mixed $entity typeof [UserAccount | integer]
     * @return UserAccount
     * @throws Exception
     */
    public function find($entity) {
        $repository = $this->instance()->getRepository('GitboxCoreBundle:UserAccount');

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
     * @param $content UserAccount
     * @throws Exception
     */
    public function remove($content) {
        if ($content instanceof Content) {
            $this->instance()->remove($content);
            $this->instance()->flush();
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }
    }

    /**
     * @param $content UserAccount
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
     * @param $content UserAccount
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
}