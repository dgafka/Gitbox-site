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
     * Pobieranie dodatkowych informacji o użytkowniku po identyfikatorze
     *
     * @param int $userId
     * @return UserFavContent | null
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    public function findByUserId($userId) {
        if (is_int($userId)) {
            $queryBuilder = $this->instance()->createQueryBuilder();

            $queryBuilder
                ->select('fav')
                ->from('GitboxCoreBundle:UserFavContent', 'fav')
                ->innerJoin('GitboxCoreBundle:UserAccount', 'u', JOIN::WITH, 'u.id = fav.idUser')
                ->where('u.id = :user_id')
                ->setParameters(array(
                    'user_id' => $userId,
                ));
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }

        try {
            return $queryBuilder->getQuery()->getResult();
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