<?php

namespace Gitbox\Bundle\CoreBundle\Helper;

use Gitbox\Bundle\CoreBundle\Entity\UserFavContent;
use Symfony\Component\Config\Definition\Exception\Exception;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\DBAL\Connection;


/**
 * Klasa wspomagająca obsługę dodatkowych aktywności użytkowników
 * np. głosowanie, ulubione treści
 *
 * Class UserActivitiesHelper
 * @package Gitbox\Bundle\CoreBundle\Helper
 */
class UserActivitiesHelper extends EntityHelper {

    /**
     * @var Connection
     */
    private $connection;

    public function __construct($entityManager, $cacheHelper, $connection) {
        parent::__construct($entityManager, $cacheHelper);

        $this->connection = $connection;
    }

    /**
     * Dodanie/usunięcie Content-u z listy ulubionych
     *
     * @param int $userId
     * @param int $contentId
     *
     * @return array
     *
     * @throws \Symfony\Component\Config\Definition\Exception\Exception
     */
    public function toggleFav($userId, $contentId) {
        // walidacja parametrów
        if (!(is_int($userId) && is_int($contentId))) {
            throw new Exception('Niepoprawny typ parametru.');
        }

        $queryBuilder = $this->instance()->createQueryBuilder();

        $data = $this->getFavData($userId, $contentId);

        // dodaj/usuń ulubiony Content
        if (!isset($data)) {
            $result = $this->connection
                ->insert('user_fav_content', array(
                    'id_user' => $userId,
                    'id_content' => $contentId
                ));

            if (!$result) {
                return array(
                    'success' => false,
                    'msg' => 'Wystąpił błąd podczas dodawania ulubionych! Odśwież stronę i spróbuj ponownie.'
                );
            }

            return array(
                'success' => true,
                'status' => 'added',
                'msg' => 'Dodano wpis do ulubionych.'
            );
        } else {
            $result = $queryBuilder
                ->delete('GitboxCoreBundle:UserFavContent', 'fav')
                ->where('fav.idUser = :user_id AND fav.idContent = :content_id')
                ->setParameters(array(
                    'user_id' => $userId,
                    'content_id' => $contentId
                ))
                ->getQuery()->execute();

            if (!$result) {
                return array(
                    'success' => false,
                    'msg' => 'Wystąpił błąd podczas usuwania ulubionych! Odśwież stronę i spróbuj ponownie.'
                );
            }

            return array(
                'success' => true,
                'status' => 'removed',
                'msg' => 'Usunięto wpis z ulubionych.'
            );
        }
    }

    /**
     * Aktualizacja liczby głosow dla rekordu w tabeli Content oraz sumy, i ilości głosów w formie statystyk użytkownika
     *
     * @param $contentId
     * @param string $type 'up'|'down'
     * @param $userId
     *
     * @return array
     */
    public function doVote($contentId, $type, $userId) {
        if ($type == 'up' || $type == 'down') {
            $voteType = ucfirst($type);

            $queryBuilder = $this->instance()->createQueryBuilder();

            // pobranie potrzebnych danych
            $data = $this->getVoteData($contentId);

            if (!isset($data)) {
                return array('success' => false, 'msg' => 'Nie możesz głosować na nieistniejący atrykuł!');
            } else if ($data['user_id'] == $userId) {
                return array('success' => false, 'msg' => 'Nie możesz głosować na swoje dzieło!');
            }

            // zaktualizowanie liczby głosów dla rekordu w tabeli Content
            $queryBuilder
                ->update('GitboxCoreBundle:Content', 'c')
                ->set('c.vote' . $voteType , 'c.vote' . $voteType . ' + 1')
                ->where('c.id = :content_id')
                ->setParameters(array(
                    'content_id' => $contentId
                ))
                ->getQuery()->execute();

            // zaktualizuj pobrane wcześniej dane
            $data['vote' . $voteType]++;

            // zapobiegnięcie akumulowaniu się pól do zaktualizowania
            $queryBuilder = $this->instance()->createQueryBuilder();

            $scoreModifier = $type == 'up' ? ' + 1' : ' - 1';

            // zaktualizowanie sumy głosów dla użytkownika (DQL nie obsługuje JOIN-ów)
            $queryBuilder
                ->update('GitboxCoreBundle:UserDescription', 'ud')
                ->set('ud.ratingScore', 'ud.ratingScore' . $scoreModifier)
                ->set('ud.ratingQuantity', 'ud.ratingQuantity + 1')
                ->where('ud.id = :user_description_id')
                ->setParameters(array(
                    'user_description_id' => $data['user_description_id']
                ))
                ->getQuery()->execute();

            return array(
                'success' => true,
                'msg' => 'Zagłosowałeś pomyślnie.',
                'votesUp' => $data['voteUp'],
                'votesDown' => $data['voteDown']
            );
        }

        return array('success' => false, 'msg' => 'Wystąpił błąd podczas głosowania! Odśwież stronę i spróbuj ponownie.');
    }

    /**
     * @param $contentId
     *
     * @return mixed
     */
    private function getVoteData($contentId) {
        $queryBuilder = $this->instance()->createQueryBuilder();

        // pobranie potrzebnych danych
        $data = $queryBuilder
            ->select('c.voteUp, c.voteDown, u.id AS user_id, ud.id AS user_description_id')
            ->from('GitboxCoreBundle:Content', 'c')
            ->innerJoin('GitboxCoreBundle:UserAccount', 'u', JOIN::WITH, 'c.idUser = u.id')
            ->innerJoin('GitboxCoreBundle:UserDescription', 'ud', JOIN::WITH, 'u.idDescription = ud.id')
            ->where('c.id = :content_id')
            ->setParameters(array(
                'content_id' => $contentId
            ))
            ->getQuery()->getOneOrNullResult();

        return $data;
    }

    /**
     * @param $userId
     * @param $contentId
     *
     * @return mixed
     */
    private function getFavData($userId, $contentId) {
        $queryBuilder = $this->instance()->createQueryBuilder();

        // pobranie danych
        $data = $queryBuilder
            ->select('fav')
            ->from('GitboxCoreBundle:UserFavContent', 'fav')
            ->innerJoin('fav.idUser', 'u')
            ->innerJoin('fav.idContent', 'c')
            ->where('fav.idUser = :user_id AND fav.idContent = :content_id')
            ->setParameters(array(
                'user_id' => $userId,
                'content_id' => $contentId
            ))
            ->getQuery()->getOneOrNullResult();

        return $data;
    }
}