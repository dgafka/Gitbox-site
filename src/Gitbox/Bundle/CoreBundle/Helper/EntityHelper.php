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
	 * @var \Gitbox\Bundle\CoreBundle\Helper\CacheHelper
	 */
	protected static $cacheHelper;

    /**
     * Pobranie entity managera z containera symfony
     *
     * @param $entityManager
     * @param $cacheHelper
     */
    public function __construct($entityManager, $cacheHelper) {
        self::$em = $entityManager;
	    self::$cacheHelper = $cacheHelper;
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

	/**
	 * @return CacheHelper
	 */
	public function instanceCache() {
		return self::$cacheHelper;
	}
}