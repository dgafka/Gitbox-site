<?php

namespace Gitbox\Bundle\CoreBundle\Helper;

use Gitbox\Bundle\CoreBundle\Entity\UserDescription;
use Symfony\Component\Config\Definition\Exception\Exception;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query;

/**
 * Klasa wspomagająca pobieranie dodatkowych informacji o użytkowniku z bazy
 *
 * Class UserDescriptionHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
class UserDescriptionHelper extends EntityHelper implements CRUDHelper {

    public function __construct($entityManager, $cacheHelper) {
        parent::__construct($entityManager, $cacheHelper);
    }

    /**
     * @param mixed $entity
     * @return UserDescription
     * @throws Exception
     */
    public function find($entity) {
        $repository = $this->instance()->getRepository('GitboxCoreBundle:UserDescription');

        if ($entity instanceof UserDescription) {
            $userDescription = $repository->find($entity->getId());
        } else if (is_int($entity)) {
            $userDescription = $repository->find($entity);
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }

        return $userDescription;
    }


    /**
     * Pobieranie dodatkowych informacji o użytkowniku po identyfikatorze
     *
     * @param int $userId
     * @return UserDescription | null
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    public function findByUserId($userId) {
        if (is_int($userId)) {
            $queryBuilder = $this->instance()->createQueryBuilder();

            $queryBuilder
                ->select('ud')
                ->from('GitboxCoreBundle:UserDescription', 'ud')
                ->innerJoin('GitboxCoreBundle:UserAccount', 'u', JOIN::WITH, 'u.idDescription = ud.id')
                ->where('u.id = :user_id')
                ->setParameters(array(
                    'user_id' => $userId,
                ));
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }

        try {
            return $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * Pobieranie dodatkowych informacji o użytkowniku po jego loginie
     *
     * @param string $login
     * @return UserDescription | null
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    public function findByLogin($login) {
        if (isset($login)) {
            $queryBuilder = $this->instance()->createQueryBuilder();

            $queryBuilder
                ->select('ud')
                ->from('GitboxCoreBundle:UserDescription', 'ud')
                ->innerJoin('GitboxCoreBundle:UserAccount', 'u', JOIN::WITH, 'u.idDescription = ud.id')
                ->where('u.login = :user_login')
                ->setParameters(array(
                    'user_login' => $login,
                ));
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }

        try {
            return $queryBuilder->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * @return array
     */
    public function findAll() {
        $usersDescription = $this->instance()
            ->getRepository('GitboxCoreBundle:UserDescription')
            ->findAll();

        return $usersDescription;
    }

    /**
     * @param mixed $userDescription
     * @throws Exception
     */
    public function remove($userDescription) {
        if ($userDescription instanceof UserDescription) {
            $this->instance()->remove($userDescription);
            $this->instance()->flush();
        } else if (is_int($userDescription)) {
            $queryBuilder = $this->instance()->createQueryBuilder();
            $queryBuilder
                ->delete('GitboxCoreBundle:UserDescription', 'ud')
                ->where('ud.id = :id')
                ->setParameter('id', $userDescription)
                ->getQuery()
                ->execute();
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }
    }

    /**
     * @param $userDescription UserDescription
     * @throws Exception
     */
    public function update($userDescription) {
        if ($userDescription instanceof UserDescription) {
            $this->instance()->persist($userDescription);
            $this->instance()->flush();
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }
    }

    /**
     * @param $userDescription UserDescription
     * @throws Exception
     */
    public function insert($userDescription) {
        if ($userDescription instanceof UserDescription) {
            $this->instance()->persist($userDescription);
            $this->instance()->flush();
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }
    }

    /**
     * Aktualizacja oceny oraz sumy głosów
     *
     * @param int|string $user
     * @param $ratingUp
     * @param $ratingDown
     *
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    public function updateUserScore($user, $ratingUp, $ratingDown) {
        // walidacja parametrów
        if (is_string($user)) {
            $userId = $this->instanceCache()->getUserIdByLogin($user);
        } else if (is_int($user)) {
            $userId = $user;
        } else {
            throw new Exception('Niepoprawny typ parametru.');
        }

        if (!(is_int($ratingUp) && is_int($ratingDown))) {
            throw new Exception('Niepoprawny typ parametru.');
        }

        $userDesc = $this->findByUserId($userId);

        $ratingScore = ($ratingUp - $ratingDown) * -1;
        $ratingTotal = $ratingUp + $ratingDown;

        $userDesc->setRatingScore($userDesc->getRatingScore() + $ratingScore);
        $userDesc->setRatingQuantity($userDesc->getRatingQuantity() - $ratingTotal);

        $this->update($userDesc);
    }
}