<?php

namespace Gitbox\Bundle\CoreBundle\Helper;

use Gitbox\Bundle\CoreBundle\Entity\UserFavContent;
use Symfony\Component\Config\Definition\Exception\Exception;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query;

/**
 * Klasa serwisowa do pobierania ulubionych Content-ów użytkownika
 *
 * Class FavContentHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
class FavContentHelper extends EntityHelper implements CRUDHelper {

    public function __construct($entityManager, $cacheHelper) {
        parent::__construct($entityManager, $cacheHelper);
    }

    /**
     * @param UserFavContent $entity
     * @return UserFavContent
     * @throws Exception
     */
    public function find($entity) {
        $repository = $this->instance()->getRepository('GitboxCoreBundle:UserFavContent');

        if ($entity instanceof UserFavContent) {
            $userFav = $repository->find($entity);
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }

        return $userFav;
    }

    /**
     * Pobranie ulubionych Content-ów użytkownika
     *
     * @param int $userId
     * @param bool $sortByModule
     * @return UserFavContent | null
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    public function findByUserId($userId, $sortByModule = false) {
        if (is_int($userId)) {
            $queryBuilder = $this->instance()->createQueryBuilder();

            $queryBuilder
                ->select('fav')
                ->from('GitboxCoreBundle:UserFavContent', 'fav')
                ->innerJoin('GitboxCoreBundle:UserAccount', 'u', JOIN::WITH, 'u.id = fav.idUser');

            if ($sortByModule) {
                $queryBuilder
                    ->addSelect('module.id as moduleId, c')
                    ->innerJoin('fav.idContent', 'c')
                    ->innerJoin('c.idMenu', 'm')
                    ->innerJoin('m.idModule', 'module');
            }

            $queryBuilder
                ->where('u.id = :user_id')
                ->setParameters(array(
                    'user_id' => $userId,
                ));
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }

        try {
            $favContents = $queryBuilder->getQuery()->getResult();

            if ($sortByModule) {
                $queryBuilder = $this->instance()->createQueryBuilder();

                $queryBuilder
                    ->select('mod')
                    ->from('GitboxCoreBundle:Module', 'mod');

                $modules = $queryBuilder->getQuery()->getArrayResult();

                foreach ($modules as &$module) {
                    $module['items'] = array();
                }

                foreach ($favContents as $favContent) {
                    foreach ($modules as &$module) {
                        if ($module['id'] === $favContent['moduleId']) {
                            $module['items'][] = $favContent[0];
                        }
                    }
                }

                return $modules;
            }

            return $favContents;
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @return array
     */
    public function findAll() {
        $userFavs = $this->instance()
            ->getRepository('GitboxCoreBundle:UserFavContent')
            ->findAll();

        return $userFavs;
    }

    /**
     * @param mixed $userFav
     * @throws Exception
     */
    public function remove($userFav) {
        if ($userFav instanceof UserFavContent) {
            $this->instance()->remove($userFav);
            $this->instance()->flush();
        } else if (is_int($userFav)) {
            $queryBuilder = $this->instance()->createQueryBuilder();
            $queryBuilder
                ->delete('GitboxCoreBundle:UserFavContent', 'ud')
                ->where('ud.id = :id')
                ->setParameter('id', $userFav)
                ->getQuery()
                ->execute();
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }
    }

    /**
     * @param $userFav UserFavContent
     * @throws Exception
     */
    public function update($userFav) {
        if ($userFav instanceof UserFavContent) {
            $this->instance()->persist($userFav);
            $this->instance()->flush();
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }
    }

    /**
     * @param $userFav UserFavContent
     * @throws Exception
     */
    public function insert($userFav) {
        if ($userFav instanceof UserFavContent) {
            $this->instance()->persist($userFav);
            $this->instance()->flush();
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }
    }
}