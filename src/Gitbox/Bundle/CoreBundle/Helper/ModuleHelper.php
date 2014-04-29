<?php

namespace Gitbox\Bundle\CoreBundle\Helper;

use Gitbox\Bundle\CoreBundle\Entity\Module;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class ModuleHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
class ModuleHelper extends EntityHelper {

    /**
     * Nazwa modułu w bazie danych
     *
     * @var String
     */
    private $module;

    public function __construct($entityManager) {
        parent::__construct($entityManager);
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
     * Zwraca moduł w postaci rekordu z bazy danych
     *
     * @return Module
     * @throws Exception
     */
    public function findModule() {
        if (!isset($this->module)) {
            throw new Exception("Brak zdefiniowanej nazwy modułu.");
        }

        $module = $this->instance()
            ->getRepository('GitboxCoreBundle:Module')
            ->findOneBy(array('name' => $this->module));

        return $module;
    }

    /**
     * Sprawdza czy istnieje moduł o podanej nazwie w bazie danych
     *
     * @return bool
     */
    public function isModule() {
        if ($this->findModule() instanceof Module) {
            return true;
        }

        return false;
    }

    /**
     * Sprawdza czy podany użytkownik posiada aktywowany moduł
     *
     * @param $userLogin
     * @return bool
     * @throws Exception
     */
    public function isModuleActivated($userLogin) {
        if (!isset($this->module)) {
            throw new Exception("Brak zdefiniowanej nazwy modułu.");
        }

        $queryBuilder = $this->instance()->createQueryBuilder();

        $queryBuilder
            ->select('m')
            ->from('GitboxCoreBundle:Module', 'm')
            ->innerJoin('m.idUser', 'ua')
            ->where('ua.login = :login AND m.name = :module')
            ->setParameters(array(
                'login' => $userLogin,
                'module' => $this->module
            ));

        try {
            $queryBuilder->getQuery()->getSingleResult(); //getOneOrNullResult is nice too

            return true;
        } catch (NoResultException $e) {
            return false;
        }
    }

	/** Zwraca moduły użytkownika
	 * @param $userLogin
	 */
	public function getUserModules($userLogin) {
		$queryBuilder = $this->instance()->createQueryBuilder();

		$queryBuilder
			->select('m')
			->from('GitboxCoreBundle:Module', 'm')
			->innerJoin('m.idUser', 'ua')
			->where('ua.login = :login')
			->setParameters(array(
				'login' => $userLogin,
			));

		$results = $queryBuilder->getQuery()->execute();

		return $results;
	}

}