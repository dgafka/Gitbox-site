<?php

namespace Gitbox\Bundle\CoreBundle\Helper;

use Doctrine\Common\Persistence\ObjectManager;


/**
 * Klasa abstrakcyjna umożliwiająca dostęp do EntityManager-a.
 *
 * Class EntityHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
abstract class EntityHelper {

    /**
     * @var ObjectManager
     */
    protected static $em;

    /**
     * Pobranie entity managera z containera symfony
     *
     * @param $entityManager
     */
    public function __construct($entityManager) {
        self::$em = $entityManager;
    }

    /**
     * @return ObjectManager
     */
    public function instance() {
        if(!isset(self::$em)) {
            self::$em = $this->getDoctrine()->getManager();
        }

        return self::$em;
    }
}