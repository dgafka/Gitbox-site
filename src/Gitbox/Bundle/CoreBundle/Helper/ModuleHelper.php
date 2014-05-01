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

        $userId = $this->instanceCache()->getUserIdByLogin($userLogin);
        $moduleId = $this->instanceCache()->getModuleIdByName($this->module);

        $queryBuilder = $this->instance()->createQueryBuilder();
        $queryBuilder
            ->select('um')
            ->from('GitboxCoreBundle:UserModules', 'um')
            ->where('um.idUser = :user_id AND um.idModule = :module_id AND um.status = \'A\'')
            ->setParameters(array(
                'user_id' => $userId,
                'module_id' => $moduleId
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
		$userId       = $this->instanceCache()->getUserIdByLogin($userLogin);

		$queryBuilder
			->select('m')
			->from('GitboxCoreBundle:Module', 'm')
			->innerJoin('GitboxCoreBundle:UserModules', 'um', 'WITH', 'um.idModule = m.id')
			->where('um.idUser = :user')
			->setParameters(array(
				'user' => $userId
			));


		$results = $queryBuilder->getQuery()->execute();

		return $results;
	}

}